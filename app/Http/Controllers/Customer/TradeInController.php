<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\TradeInRequest;
use App\Services\TradeInValuationService;
use Illuminate\Http\Request;
use Inertia\Inertia;

class TradeInController extends Controller
{
    public function index()
    {
        $products = Product::where('is_active', true)
            ->select('id', 'name', 'slug', 'base_price', 'condition')
            ->with('brand:id,name')
            ->orderBy('name')
            ->get()
            ->map(fn($p) => [
                'id' => $p->id,
                'name' => $p->name,
                'slug' => $p->slug,
                'brand' => $p->brand?->name,
                'price' => (float) $p->base_price,
            ]);

        return Inertia::render('Customer/TradeIn', [
            'products' => $products,
        ]);
    }

    public function estimate(Request $request)
    {
        $validated = $request->validate([
            'product_id' => 'nullable|exists:products,id',
            'condition' => 'required|in:flawless,good,fair,broken',
            'battery_health' => 'nullable|integer|min:0|max:100',
            'storage_capacity' => 'nullable|string',
            'has_cracks' => 'boolean',
            'has_repairs' => 'boolean',
            'problems_description' => 'nullable|string|max:500',
        ]);

        $product = $validated['product_id'] ? Product::find($validated['product_id']) : null;

        if (!$product) {
            return response()->json([
                'estimated_value' => 0,
                'breakdown' => ['error' => 'Select a device to get a valuation'],
            ]);
        }

        $result = TradeInValuationService::calculate(
            $product,
            $validated['condition'],
            $validated['battery_health'] ?? null,
            $validated['storage_capacity'] ?? null,
            $validated['has_cracks'] ?? false,
            $validated['has_repairs'] ?? false,
            $validated['problems_description'] ?? null
        );

        return response()->json($result);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'product_id' => 'nullable|exists:products,id',
            'custom_device_name' => 'nullable|string|max:255',
            'customer_name' => 'required|string|max:100',
            'customer_phone' => 'required|string|max:20',
            'storage_capacity' => 'nullable|string|max:20',
            'condition' => 'required|in:flawless,good,fair,broken',
            'battery_health' => 'nullable|integer|min:0|max:100',
            'has_cracks' => 'boolean',
            'has_repairs' => 'boolean',
            'problems_description' => 'nullable|string|max:1000',
            'estimated_value' => 'nullable|numeric|min:0',
        ]);

        $tradeIn = TradeInRequest::create($validated);

        return redirect()->back()->with('success', 'Trade-in request submitted! We\'ll contact you within 24 hours on ' . $validated['customer_phone']);
    }
}
