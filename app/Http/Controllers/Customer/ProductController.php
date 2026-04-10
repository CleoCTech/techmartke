<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::with(['images', 'brand', 'category', 'variants'])
            ->where('is_active', true);

        // Search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'LIKE', "%{$search}%")
                  ->orWhere('description', 'LIKE', "%{$search}%")
                  ->orWhereHas('brand', fn($b) => $b->where('name', 'LIKE', "%{$search}%"));
            });
        }

        // Categories — supports: categories[]=id, category_id=id, category=slug
        // Always includes child categories when a parent is selected
        if ($request->filled('categories')) {
            $catIds = (array) $request->categories;
            $childIds = Category::whereIn('parent_id', $catIds)->pluck('id')->toArray();
            $query->whereIn('category_id', array_merge($catIds, $childIds));
        } elseif ($request->filled('category_id')) {
            $catId = $request->category_id;
            $childIds = Category::where('parent_id', $catId)->pluck('id')->toArray();
            $query->whereIn('category_id', array_merge([$catId], $childIds));
        } elseif ($request->filled('category')) {
            $cat = Category::where('slug', $request->category)
                ->orWhere('name', 'LIKE', $request->category)
                ->first();
            if ($cat) {
                $childIds = Category::where('parent_id', $cat->id)->pluck('id')->toArray();
                $query->whereIn('category_id', array_merge([$cat->id], $childIds));
            }
        }

        // Brands — supports: brands[]=id, brand_id=id, brand=slug
        if ($request->filled('brands')) {
            $query->whereIn('brand_id', (array) $request->brands);
        } elseif ($request->filled('brand_id')) {
            $query->where('brand_id', $request->brand_id);
        } elseif ($request->filled('brand')) {
            $brand = Brand::where('slug', $request->brand)
                ->orWhere('name', 'LIKE', $request->brand)
                ->first();
            if ($brand) $query->where('brand_id', $brand->id);
        }

        // Condition — normalize case
        if ($request->filled('condition')) {
            $query->where('condition', strtolower($request->condition));
        }

        // Price range — supports both naming conventions
        $minPrice = $request->price_min ?? $request->min_price;
        $maxPrice = $request->price_max ?? $request->max_price;
        if ($minPrice) $query->where('base_price', '>=', $minPrice);
        if ($maxPrice) $query->where('base_price', '<=', $maxPrice);

        $products = $query
            ->orderByRaw("CASE WHEN stock_status = 'in_stock' THEN 0 ELSE 1 END")
            ->orderBy('updated_at', 'desc')
            ->paginate(12);

        // Lightweight product hints for instant search suggestions
        $searchHints = Product::where('is_active', true)
            ->select('id', 'name', 'slug', 'brand_id', 'base_price', 'condition')
            ->with('brand:id,name')
            ->orderBy('name')
            ->get()
            ->map(fn($p) => [
                'name' => $p->name,
                'slug' => $p->slug,
                'brand' => $p->brand?->name,
                'price' => (float) $p->base_price,
                'condition' => $p->condition,
            ]);

        return Inertia::render('Customer/Products/Index', [
            'products' => $products,
            'filters' => $request->only(['search', 'categories', 'brands', 'condition', 'price_min', 'price_max', 'category', 'brand']),
            'brands' => Brand::where('is_active', true)->get(),
            'categories' => Category::where('is_active', true)->get(),
            'searchHints' => $searchHints,
        ]);
    }

    public function show($slug)
    {
        $product = Product::with(['variants', 'images', 'specifications', 'advantages', 'brand', 'category'])
            ->where('slug', $slug)
            ->where('is_active', true)
            ->firstOrFail();

        $relatedProducts = Product::with(['images', 'brand', 'variants'])
            ->where('is_active', true)
            ->where('category_id', $product->category_id)
            ->where('id', '!=', $product->id)
            ->limit(4)
            ->get();

        return Inertia::render('Customer/Products/Show', [
            'product' => $product,
            'relatedProducts' => $relatedProducts,
        ]);
    }
}
