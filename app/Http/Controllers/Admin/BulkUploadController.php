<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BulkPriceUpload;
use App\Models\Product;
use App\Models\ProductVariant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Inertia\Inertia;

class BulkUploadController extends Controller
{
    public function __construct()
    {
        $this->middleware('gen-auth');
        $this->middleware('admin-auth');
    }

    public function index()
    {
        return Inertia::render('Admin/Products/BulkUpload');
    }

    public function parse(Request $request)
    {
        $validated = $request->validate([
            'raw_text' => 'required|string',
        ]);

        $lines = explode("\n", trim($validated['raw_text']));
        $parsed = [];

        foreach ($lines as $line) {
            $line = trim($line);
            if (empty($line)) {
                continue;
            }

            if (preg_match('/^(\d+)\s*(Pro|Pro Max)?\s*(\d+GB)\s*(eSIM|Physical)?\s*-\s*([\d,]+)/', $line, $matches)) {
                $parsed[] = [
                    'model_number' => $matches[1],
                    'variant_name' => trim($matches[2] ?? ''),
                    'storage' => $matches[3],
                    'sim_type' => trim($matches[4] ?? ''),
                    'price' => (float) str_replace(',', '', $matches[5]),
                ];
            }
        }

        return response()->json([
            'parsed' => $parsed,
            'total' => count($parsed),
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'raw_text' => 'required|string',
            'parsed_data' => 'required|array',
            'parsed_data.*.model_number' => 'required|string',
            'parsed_data.*.variant_name' => 'nullable|string',
            'parsed_data.*.storage' => 'required|string',
            'parsed_data.*.sim_type' => 'nullable|string',
            'parsed_data.*.price' => 'required|numeric|min:0',
            'brand_id' => 'nullable|exists:brands,id',
            'category_id' => 'nullable|exists:categories,id',
        ]);

        DB::beginTransaction();
        try {
            $productsCreated = 0;
            $variantsCreated = 0;
            $errors = [];

            foreach ($validated['parsed_data'] as $item) {
                $productName = 'iPhone ' . $item['model_number'];
                if (!empty($item['variant_name'])) {
                    $productName .= ' ' . $item['variant_name'];
                }

                $product = Product::firstOrCreate(
                    ['slug' => Str::slug($productName)],
                    [
                        'name' => $productName,
                        'brand_id' => $validated['brand_id'] ?? null,
                        'category_id' => $validated['category_id'] ?? null,
                        'base_price' => $item['price'],
                        'is_active' => true,
                        'condition' => 'new',
                    ]
                );

                if ($product->wasRecentlyCreated) {
                    $productsCreated++;
                }

                $variant = $product->variants()->create([
                    'storage' => $item['storage'],
                    'sim_type' => $item['sim_type'] ?: null,
                    'price' => $item['price'],
                    'stock_quantity' => 0,
                    'is_active' => true,
                    'sku' => Str::slug($productName) . '-' . Str::slug($item['storage']),
                ]);

                $variantsCreated++;
            }

            $upload = BulkPriceUpload::create([
                'uploaded_by' => Auth::id(),
                'raw_text' => $validated['raw_text'],
                'parsed_data' => $validated['parsed_data'],
                'status' => 'completed',
                'products_created' => $productsCreated,
                'variants_created' => $variantsCreated,
                'errors' => $errors,
            ]);

            DB::commit();

            return redirect()->back()->with('success', "Bulk upload completed: {$productsCreated} products, {$variantsCreated} variants created.");
        } catch (\Throwable $th) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Bulk upload failed: ' . $th->getMessage());
        }
    }
}
