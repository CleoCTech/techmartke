<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use App\Models\ProductImage;
use App\Models\ProductVariant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Inertia\Inertia;

class ProductController extends Controller
{
    public function __construct()
    {
        $this->middleware('gen-auth');
        $this->middleware('admin-auth');
    }

    public function index(Request $request)
    {
        $query = Product::with(['brand', 'category', 'variants']);

        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'LIKE', "%{$search}%")
                  ->orWhere('base_sku', 'LIKE', "%{$search}%");
            });
        }

        if ($request->has('category_id') && $request->category_id) {
            $query->where('category_id', $request->category_id);
        }

        if ($request->has('brand_id') && $request->brand_id) {
            $query->where('brand_id', $request->brand_id);
        }

        $products = $query->orderBy('created_at', 'desc')->paginate(15);

        return Inertia::render('Admin/Products/Index', [
            'products' => $products,
            'filters' => $request->only(['search', 'category_id', 'brand_id']),
            'brands' => Brand::where('is_active', true)->get(),
            'categories' => Category::where('is_active', true)->get(),
        ]);
    }

    public function create()
    {
        return Inertia::render('Admin/Products/Create', [
            'brands' => Brand::where('is_active', true)->get(),
            'categories' => Category::where('is_active', true)->get(),
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'brand_id' => 'required|exists:brands,id',
            'category_id' => 'required|exists:categories,id',
            'description' => 'nullable|string',
            'short_description' => 'nullable|string|max:500',
            'condition' => 'nullable|string|max:50',
            'base_price' => 'required|numeric|min:0',
            'original_price' => 'nullable|numeric|min:0',
            'cost_price' => 'nullable|numeric|min:0',
            'base_sku' => 'nullable|string|max:100',
            'is_active' => 'boolean',
            'featured' => 'boolean',
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string|max:500',
            'variants' => 'nullable|array',
            'variants.*.storage' => 'nullable|string|max:50',
            'variants.*.sim_type' => 'nullable|string|max:50',
            'variants.*.color' => 'nullable|string|max:50',
            'variants.*.price' => 'required_with:variants|numeric|min:0',
            'variants.*.cost_price' => 'nullable|numeric|min:0',
            'variants.*.stock_quantity' => 'nullable|integer|min:0',
            'variants.*.sku' => 'nullable|string|max:100',
            'images' => 'nullable|array',
            'images.*.image_url' => 'required_with:images|string',
            'images.*.alt_text' => 'nullable|string|max:255',
            'images.*.is_primary' => 'boolean',
        ]);

        DB::beginTransaction();
        try {
            $product = Product::create([
                'name' => $validated['name'],
                'slug' => Str::slug($validated['name']),
                'brand_id' => $validated['brand_id'],
                'category_id' => $validated['category_id'],
                'description' => $validated['description'] ?? null,
                'short_description' => $validated['short_description'] ?? null,
                'condition' => $validated['condition'] ?? 'new',
                'base_price' => $validated['base_price'],
                'original_price' => $validated['original_price'] ?? null,
                'cost_price' => $validated['cost_price'] ?? null,
                'base_sku' => $validated['base_sku'] ?? null,
                'is_active' => $validated['is_active'] ?? true,
                'featured' => $validated['featured'] ?? false,
                'meta_title' => $validated['meta_title'] ?? null,
                'meta_description' => $validated['meta_description'] ?? null,
            ]);

            if (!empty($validated['variants'])) {
                foreach ($validated['variants'] as $variant) {
                    $product->variants()->create($variant);
                }
            }

            if (!empty($validated['images'])) {
                foreach ($validated['images'] as $image) {
                    $product->images()->create($image);
                }
            }

            DB::commit();

            return redirect()->back()->with('success', 'Product created successfully.');
        } catch (\Throwable $th) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Failed to create product: ' . $th->getMessage());
        }
    }

    public function show($id)
    {
        $product = Product::with(['brand', 'category', 'variants', 'images', 'specifications', 'advantages'])
            ->findOrFail($id);

        return Inertia::render('Admin/Products/Show', [
            'product' => $product,
        ]);
    }

    public function edit($id)
    {
        $product = Product::with(['variants', 'images', 'specifications', 'advantages'])
            ->findOrFail($id);

        return Inertia::render('Admin/Products/Edit', [
            'product' => $product,
            'brands' => Brand::where('is_active', true)->get(),
            'categories' => Category::where('is_active', true)->get(),
        ]);
    }

    public function update(Request $request, $id)
    {
        $product = Product::findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'brand_id' => 'required|exists:brands,id',
            'category_id' => 'required|exists:categories,id',
            'description' => 'nullable|string',
            'short_description' => 'nullable|string|max:500',
            'condition' => 'nullable|string|max:50',
            'base_price' => 'required|numeric|min:0',
            'original_price' => 'nullable|numeric|min:0',
            'cost_price' => 'nullable|numeric|min:0',
            'base_sku' => 'nullable|string|max:100',
            'is_active' => 'boolean',
            'featured' => 'boolean',
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string|max:500',
        ]);

        DB::beginTransaction();
        try {
            $product->update($validated);
            DB::commit();

            return redirect()->back()->with('success', 'Product updated successfully.');
        } catch (\Throwable $th) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Failed to update product: ' . $th->getMessage());
        }
    }

    public function destroy($id)
    {
        $product = Product::findOrFail($id);

        DB::beginTransaction();
        try {
            $product->variants()->delete();
            $product->images()->delete();
            $product->specifications()->delete();
            $product->delete();
            DB::commit();

            return redirect()->back()->with('success', 'Product deleted successfully.');
        } catch (\Throwable $th) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Failed to delete product: ' . $th->getMessage());
        }
    }
}
