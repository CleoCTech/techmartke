<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ComparisonController extends Controller
{
    public function compare(Request $request)
    {
        $validated = $request->validate([
            'budget' => 'required|numeric|min:0',
        ]);

        $budget = $validated['budget'];
        $minPrice = $budget * 0.95;
        $maxPrice = $budget * 1.05;

        $products = Product::with(['brand', 'category', 'variants', 'images', 'specifications'])
            ->where('is_active', true)
            ->inBudget($minPrice, $maxPrice)
            ->orderBy('base_price')
            ->get();

        return Inertia::render('Customer/Comparison', [
            'products' => $products,
            'budget' => $budget,
            'priceRange' => [
                'min' => $minPrice,
                'max' => $maxPrice,
            ],
        ]);
    }
}
