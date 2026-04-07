<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BulkPriceUpload;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\Brand;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use App\Services\ProductImageService;
use App\Services\ProductContentService;
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
            'raw_text' => 'required|string|max:10000',
        ]);

        $apiKey = config('anthropic.api_key') ?: env('ANTHROPIC_API_KEY');
        if (empty($apiKey)) {
            return response()->json([
                'error' => 'Anthropic API key is not configured. Add ANTHROPIC_API_KEY to your .env file.',
            ], 422);
        }

        // Get existing products with variants for context
        $existingProducts = Product::with(['variants', 'brand'])->get();
        $brands = Brand::all();
        $categories = Category::all();

        $productContext = $existingProducts->map(function ($p) {
            $variants = $p->variants->map(fn($v) => [
                'id' => $v->id,
                'sku' => $v->sku,
                'storage' => $v->storage,
                'sim_type' => $v->sim_type,
                'color' => $v->color,
                'price' => (float) $v->price,
                'stock_quantity' => $v->stock_quantity,
            ]);
            return [
                'id' => $p->id,
                'name' => $p->name,
                'slug' => $p->slug,
                'brand' => $p->brand?->name,
                'brand_id' => $p->brand_id,
                'category_id' => $p->category_id,
                'condition' => $p->condition,
                'base_price' => (float) $p->base_price,
                'variants' => $variants,
            ];
        })->toArray();

        $brandList = $brands->map(fn($b) => ['id' => $b->id, 'name' => $b->name])->toArray();
        $categoryList = $categories->map(fn($c) => ['id' => $c->id, 'name' => $c->name])->toArray();

        $prompt = $this->buildPrompt($validated['raw_text'], $productContext, $brandList, $categoryList);

        try {
            $response = Http::withHeaders([
                'x-api-key' => $apiKey,
                'anthropic-version' => '2023-06-01',
                'content-type' => 'application/json',
            ])->timeout(60)->post('https://api.anthropic.com/v1/messages', [
                'model' => 'claude-sonnet-4-20250514',
                'max_tokens' => 4096,
                'messages' => [
                    ['role' => 'user', 'content' => $prompt],
                ],
            ]);

            if (!$response->successful()) {
                return response()->json([
                    'error' => 'AI service error: ' . ($response->json('error.message') ?? 'Unknown error'),
                ], 422);
            }

            $aiText = $response->json('content.0.text', '');
            $parsed = $this->extractJson($aiText);

            if ($parsed === null) {
                return response()->json([
                    'error' => 'Failed to parse AI response. Please try again.',
                    'raw_response' => $aiText,
                ], 422);
            }

            return response()->json($parsed);

        } catch (\Exception $e) {
            return response()->json([
                'error' => 'AI analysis failed: ' . $e->getMessage(),
            ], 500);
        }
    }

    private function buildPrompt(string $rawText, array $existingProducts, array $brands, array $categories): string
    {
        $productsJson = json_encode($existingProducts, JSON_PRETTY_PRINT);
        $brandsJson = json_encode($brands);
        $categoriesJson = json_encode($categories);

        return <<<PROMPT
You are a product price list parser for TechMart KE, an electronics store in Kenya. Prices are in KES (Kenyan Shillings).

## Existing Products in Database
{$productsJson}

## Available Brands
{$brandsJson}

## Available Categories
{$categoriesJson}

## Price List Text to Parse
{$rawText}

## MANDATORY Price Markup Rules
You MUST apply the following markup to EVERY price before outputting it. The raw price from the text is the base/cost price. Calculate the final selling price by adding the markup based on the base price range:

- 15,000 – 20,000 → add 1,500
- 20,000 – 30,000 → add 2,500
- 30,000 – 40,000 → add 3,000
- 40,000 – 50,000 → add 4,000
- 50,000 – 55,000 → add 4,500
- 55,000 – 88,000 → add 5,000
- 89,000 and above → add 6,000
- Below 15,000 → no markup (keep as-is)

For example: if the text says "25,000" → the output price should be 27,500 (25,000 + 2,500).

## Instructions
Parse each line of the price list and determine:
1. **Product name** - the full product name (e.g. "Samsung S10e", "iPhone 15 Pro Max")
2. **Storage** - if mentioned (e.g. "128GB", "256GB", "1TB")
3. **Price** - the ADJUSTED price in KES after applying the mandatory markup rules above (remove commas, dashes, "/=" from raw price first, then apply markup)
4. **Condition** - infer from context (e.g. "Ex US", "Ex UK" = "ex-uk", "Refurbished" = "refurbished", otherwise "new")
5. **Brand** - match to an existing brand_id from the brands list, or suggest brand name if new
6. **Category** - match to existing category_id if possible

For EACH parsed product, check against the existing products:
- If the product name closely matches an existing product AND a variant with the same storage exists → mark as "update" with the existing product_id and variant_id
- If the product name matches but no variant with that storage exists → mark as "new_variant" with the existing product_id
- If no matching product exists → mark as "create"

Also detect header lines (like "Ex US SAMSUNG", "IPHONES NEW", etc.) and use them as context for the products below but don't include them as products.

## Response Format
Respond with ONLY a JSON object (no markdown, no explanation):
{
  "items": [
    {
      "action": "update|new_variant|create",
      "product_name": "Full Product Name",
      "storage": "128GB",
      "raw_price": 25000,
      "price": 27500,
      "markup_applied": 2500,
      "condition": "new|ex-uk|refurbished",
      "brand_name": "Samsung",
      "brand_id": 2,
      "category_id": 1,
      "existing_product_id": null,
      "existing_variant_id": null,
      "existing_price": null,
      "confidence": "high|medium|low",
      "notes": "optional note about the match"
    }
  ],
  "skipped": [
    {"line": "Header line text", "reason": "Detected as header/category label"}
  ],
  "summary": {
    "total_parsed": 10,
    "updates": 3,
    "new_variants": 2,
    "creates": 5,
    "skipped": 1
  }
}
PROMPT;
    }

    private function extractJson(string $text): ?array
    {
        // Try direct parse first
        $decoded = json_decode($text, true);
        if ($decoded !== null) {
            return $decoded;
        }

        // Try extracting from code block
        if (preg_match('/```(?:json)?\s*\n?(.*?)\n?```/s', $text, $matches)) {
            $decoded = json_decode($matches[1], true);
            if ($decoded !== null) {
                return $decoded;
            }
        }

        // Try finding JSON object in text
        if (preg_match('/\{[\s\S]*\}/', $text, $matches)) {
            $decoded = json_decode($matches[0], true);
            if ($decoded !== null) {
                return $decoded;
            }
        }

        return null;
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'raw_text' => 'required|string',
            'items' => 'required|array|min:1',
            'items.*.action' => 'required|in:update,new_variant,create',
            'items.*.product_name' => 'required|string',
            'items.*.storage' => 'nullable|string',
            'items.*.price' => 'required|numeric|min:0',
            'items.*.raw_price' => 'nullable|numeric|min:0',
            'items.*.condition' => 'nullable|string',
            'items.*.brand_id' => 'nullable|integer',
            'items.*.category_id' => 'nullable|integer',
            'items.*.existing_product_id' => 'nullable|integer',
            'items.*.existing_variant_id' => 'nullable|integer',
            'mark_others_out_of_stock' => 'boolean',
        ]);

        DB::beginTransaction();
        try {
            $stats = ['updated' => 0, 'new_variants' => 0, 'created' => 0, 'marked_out' => 0];
            $touchedProductIds = [];
            $touchedVariantIds = [];

            foreach ($validated['items'] as $item) {
                if (!($item['selected'] ?? true)) {
                    continue;
                }

                switch ($item['action']) {
                    case 'update':
                        if ($item['existing_variant_id']) {
                            $variant = ProductVariant::find($item['existing_variant_id']);
                            if ($variant) {
                                $variant->update([
                                    'price' => $item['price'],
                                    'stock_quantity' => max($variant->stock_quantity, 1), // Mark as available
                                    'is_active' => true,
                                ]);
                                $product = $variant->product;
                                $touchedProductIds[] = $product->id;
                                $touchedVariantIds[] = $variant->id;

                                // Update product base_price and stock_status
                                $minPrice = $product->variants()->min('price');
                                $product->update([
                                    'base_price' => $minPrice,
                                    'stock_status' => 'in_stock',
                                    'condition' => $item['condition'] ?? $product->condition,
                                ]);
                                $stats['updated']++;
                            }
                        }
                        break;

                    case 'new_variant':
                        if ($item['existing_product_id']) {
                            $product = Product::find($item['existing_product_id']);
                            if ($product) {
                                $sku = Str::slug($product->name) . '-' . Str::slug($item['storage'] ?? 'default');
                                $variant = $product->variants()->updateOrCreate(
                                    ['storage' => $item['storage'] ?? null],
                                    [
                                        'price' => $item['price'],
                                        'stock_quantity' => DB::raw('GREATEST(stock_quantity, 1)'),
                                        'is_active' => true,
                                        'sku' => $sku,
                                    ]
                                );
                                // Re-fetch to get actual stock_quantity after DB::raw
                                $variant->refresh();
                                $touchedProductIds[] = $product->id;
                                $touchedVariantIds[] = $variant->id;

                                $minPrice = $product->variants()->min('price');
                                $product->update([
                                    'base_price' => $minPrice,
                                    'stock_status' => 'in_stock',
                                ]);
                                $stats['new_variants']++;
                            }
                        }
                        break;

                    case 'create':
                        $slug = Str::slug($item['product_name']);
                        $baseSku = strtoupper(Str::slug($item['product_name'], '-'));
                        $product = Product::updateOrCreate(
                            ['slug' => $slug],
                            [
                                'name' => $item['product_name'],
                                'base_sku' => $baseSku,
                                'brand_id' => $item['brand_id'] ?? null,
                                'category_id' => $item['category_id'] ?? null,
                                'base_price' => $item['price'],
                                'cost_price' => $item['raw_price'] ?? null,
                                'condition' => $item['condition'] ?? 'new',
                                'stock_status' => 'in_stock',
                                'is_active' => true,
                            ]
                        );

                        $variantSku = Str::slug($item['product_name']) . '-' . Str::slug($item['storage'] ?? 'default');
                        $variant = $product->variants()->updateOrCreate(
                            ['storage' => $item['storage'] ?? null],
                            [
                                'price' => $item['price'],
                                'stock_quantity' => DB::raw('GREATEST(stock_quantity, 1)'),
                                'is_active' => true,
                                'sku' => $variantSku,
                            ]
                        );
                        $variant->refresh();
                        $touchedProductIds[] = $product->id;
                        $touchedVariantIds[] = $variant->id;
                        $stats['created']++;
                        break;
                }
            }

            // Mark products NOT in the price list as out of stock
            if ($request->boolean('mark_others_out_of_stock') && !empty($touchedProductIds)) {
                $stats['marked_out'] = Product::whereNotIn('id', array_unique($touchedProductIds))
                    ->where('stock_status', '!=', 'out_of_stock')
                    ->update(['stock_status' => 'out_of_stock']);

                // Set stock_quantity to 0 for untouched variants
                ProductVariant::whereNotIn('id', array_unique($touchedVariantIds))
                    ->where('stock_quantity', '>', 0)
                    ->update(['stock_quantity' => 0]);
            }

            BulkPriceUpload::create([
                'uploaded_by' => Auth::id(),
                'raw_text' => $validated['raw_text'],
                'parsed_data' => $validated['items'],
                'status' => 'completed',
                'products_created' => $stats['created'],
                'variants_created' => $stats['new_variants'],
                'errors' => [],
            ]);

            DB::commit();

            // Fetch AI images for products without images (after commit)
            $imagesAdded = 0;
            if (!empty($touchedProductIds)) {
                $imagesAdded = ProductImageService::fetchMissingImages(array_unique($touchedProductIds));
            }

            // Generate AI content (descriptions, specs, advantages) for products missing them
            $contentGenerated = 0;
            if (!empty($touchedProductIds)) {
                $contentGenerated = ProductContentService::generateMissingContent(array_unique($touchedProductIds));
            }

            $msg = [];
            if ($stats['updated'] > 0) $msg[] = "{$stats['updated']} prices updated";
            if ($stats['new_variants'] > 0) $msg[] = "{$stats['new_variants']} new variants added";
            if ($stats['created'] > 0) $msg[] = "{$stats['created']} new products created";
            if ($stats['marked_out'] > 0) $msg[] = "{$stats['marked_out']} products marked out of stock";
            if ($imagesAdded > 0) $msg[] = "{$imagesAdded} product images added";
            if ($contentGenerated > 0) $msg[] = "{$contentGenerated} products enriched with AI content";

            return redirect()->back()->with('success', 'Bulk upload completed: ' . implode(', ', $msg) . '.');
        } catch (\Throwable $th) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Bulk upload failed: ' . $th->getMessage());
        }
    }
}
