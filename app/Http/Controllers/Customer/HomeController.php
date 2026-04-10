<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Brand;
use App\Models\Category;
use App\Models\CustomerReview;
use App\Models\Product;
use Inertia\Inertia;

class HomeController extends Controller
{
    public function index()
    {
        $featuredProducts = Product::with(['images', 'brand', 'variants'])
            ->where('is_active', true)
            ->orderByRaw("CASE WHEN stock_status = 'in_stock' THEN 0 ELSE 1 END")
            ->orderBy('updated_at', 'desc')
            ->limit(8)
            ->get();

        $flashSaleProducts = Product::with(['images', 'brand', 'variants'])
            ->where('is_active', true)
            ->flashSale()
            ->orderBy('flash_sale_ends_at', 'asc')
            ->limit(6)
            ->get();

        $categories = Category::where('is_active', true)
            ->orderBy('sort_order')
            ->get();

        // Lightweight suggestions for autocomplete
        $searchHints = Product::where('is_active', true)
            ->select('name', 'brand_id', 'base_price', 'condition')
            ->with('brand:id,name')
            ->orderBy('name')
            ->get()
            ->map(fn($p) => [
                'name' => $p->name,
                'brand' => $p->brand?->name,
                'price' => (float) $p->base_price,
                'condition' => $p->condition,
            ]);

        $brandNames = Brand::where('is_active', true)->pluck('name')->toArray();
        $categoryNames = Category::where('is_active', true)->pluck('name')->toArray();

        $approvedReviews = CustomerReview::approved()
            ->orderByDesc('is_featured')
            ->orderByDesc('created_at')
            ->limit(6)
            ->get();
        $reviewCount = CustomerReview::approved()->count();

        return Inertia::render('Customer/Home', [
            'featuredProducts' => $featuredProducts,
            'flashSaleProducts' => $flashSaleProducts,
            'categories' => $categories,
            'searchHints' => $searchHints,
            'brandNames' => $brandNames,
            'categoryNames' => $categoryNames,
            'reviews' => $approvedReviews,
            'reviewCount' => $reviewCount,
        ]);
    }
}
