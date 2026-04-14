<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BulkPriceUpload;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\Brand;
use Illuminate\Support\Facades\Log;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use App\Services\ProductImageService;
use App\Services\ProductContentService;
use App\Jobs\EnrichProductsJob;
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

        // Get existing products with variants for context
        $existingProducts = Product::with(['variants', 'brand'])->get();
        $brands = Brand::all();
        $categories = Category::all();

        $apiKey = config('anthropic.api_key') ?: env('ANTHROPIC_API_KEY');

        // Count how many product lines are in the text. The regex parser is
        // fast AND reliable, so we prefer it unless the admin explicitly
        // wants AI for smart matching. Only use AI for small price lists
        // (under 10 lines) where the overhead is worth it and timeouts are
        // unlikely.
        $lineCount = substr_count($validated['raw_text'], "\n") + 1;
        $forceRegex = $lineCount > 10;

        // If no API key OR the input is too large, use the regex fallback parser
        if (empty($apiKey) || $forceRegex) {
            $fallback = $this->fallbackParse($validated['raw_text'], $existingProducts, $brands);
            $fallback['source'] = 'regex';
            if (empty($apiKey)) {
                $fallback['notice'] = 'AI parser unavailable (no API key). Used regex fallback parser.';
            } else {
                $fallback['notice'] = "Parsed by fast regex parser ({$lineCount} lines, {$fallback['summary']['total_parsed']} items).";
            }

            // Log what happened for debugging
            Log::info('Bulk upload regex parse', [
                'line_count' => $lineCount,
                'items_parsed' => $fallback['summary']['total_parsed'] ?? 0,
                'skipped' => count($fallback['skipped'] ?? []),
                'first_3_skipped' => array_slice($fallback['skipped'] ?? [], 0, 3),
                'sample_raw_text' => substr($validated['raw_text'], 0, 200),
            ]);

            // If regex found nothing, return actionable error instead of silent empty
            if (empty($fallback['items'])) {
                return response()->json([
                    'error' => 'Could not parse any products. Check format: expected lines like "iPhone 14 Pro 256GB - 85,000" or "Samsung S24 128GB - 80,000". Skipped: ' . count($fallback['skipped'] ?? []) . ' lines.',
                    'items' => [],
                    'skipped' => $fallback['skipped'] ?? [],
                ], 422);
            }

            return response()->json($fallback);
        }

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

        $aiFailureReason = null;

        try {
            $response = Http::withHeaders([
                'x-api-key' => $apiKey,
                'anthropic-version' => '2023-06-01',
                'content-type' => 'application/json',
            ])->timeout(120)->post('https://api.anthropic.com/v1/messages', [
                'model' => env('ANTHROPIC_MODEL', 'claude-sonnet-4-20250514'),
                // Bumped from 4096 — larger price lists (30+ items) were
                // truncating mid-JSON and triggering the "invalid JSON" fallback
                'max_tokens' => 16000,
                'messages' => [
                    ['role' => 'user', 'content' => $prompt],
                ],
            ]);

            if (!$response->successful()) {
                $aiFailureReason = 'AI service error (HTTP ' . $response->status() . '): ' . ($response->json('error.message') ?? 'Unknown error');
            } else {
                $aiText = $response->json('content.0.text', '');
                $stopReason = $response->json('stop_reason', '');
                $parsed = $this->extractJson($aiText);

                if ($parsed === null) {
                    if ($stopReason === 'max_tokens') {
                        $aiFailureReason = 'AI response was cut off (hit token limit). Try splitting your price list into smaller batches.';
                    } else {
                        $aiFailureReason = 'Failed to parse AI response (invalid JSON returned). Stop reason: ' . $stopReason;
                    }
                } elseif (empty($parsed['items'] ?? [])) {
                    $aiFailureReason = 'AI returned no parseable products from the text.';
                } else {
                    // SUCCESS: AI returned valid parsed items
                    $parsed['source'] = 'ai';
                    return response()->json($parsed);
                }
            }
        } catch (\Exception $e) {
            $aiFailureReason = 'AI analysis failed: ' . $e->getMessage();
        }

        // AI failed for some reason — fall back to regex parser
        $fallback = $this->fallbackParse($validated['raw_text'], $existingProducts, $brands);
        $fallback['source'] = 'regex';
        $fallback['notice'] = 'AI parser failed — used regex fallback. ' . $aiFailureReason;

        // If even fallback found nothing, return the AI error so user can debug
        if (empty($fallback['items'])) {
            return response()->json([
                'error' => $aiFailureReason . ' Fallback regex parser also found no products. Try a different format.',
                'items' => [],
                'skipped' => $fallback['skipped'] ?? [],
            ], 422);
        }

        return response()->json($fallback);
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
4. **Condition** - infer from context. IMPORTANT: distinguish "Ex US"/"Ex USA" → "ex-us" from "Ex UK" → "ex-uk". "Refurbished" → "refurbished", otherwise → "new".
5. **SIM Type** - if the line mentions "eSIM" → set sim_type to "eSIM". If it mentions "Physical" or "Dual" → use that. Otherwise leave null.
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
      "condition": "new|ex-uk|ex-us|refurbished",
      "sim_type": "eSIM|Physical|Dual SIM|null",
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

    /**
     * Markup rules — same as the AI prompt — applied to raw cost prices.
     */
    private function applyMarkup(int $rawPrice): array
    {
        $markup = 0;
        if ($rawPrice >= 89000) {
            $markup = 6000;
        } elseif ($rawPrice >= 55000) {
            $markup = 5000;
        } elseif ($rawPrice >= 50000) {
            $markup = 4500;
        } elseif ($rawPrice >= 40000) {
            $markup = 4000;
        } elseif ($rawPrice >= 30000) {
            $markup = 3000;
        } elseif ($rawPrice >= 20000) {
            $markup = 2500;
        } elseif ($rawPrice >= 15000) {
            $markup = 1500;
        }
        return ['price' => $rawPrice + $markup, 'markup' => $markup];
    }

    /**
     * Regex-based fallback parser for common Kenyan-style price lists.
     * Handles section headers (Brand New, Ex US iPhones, Ex US SAMSUNG)
     * and item lines for iPhones (X, Xr, Xs, 11, 11 Pro, 11 Pro Max, ...)
     * and Samsung (S10, S10+, S24U, Flip 3, Fold 5, Note 20, ...).
     */
    private function fallbackParse(string $rawText, $existingProducts, $brands): array
    {
        // Normalize unicode dashes (en-dash, em-dash, minus sign, hyphen-minus)
        // and other separators that copy-paste from WhatsApp/Word can introduce
        $rawText = str_replace(
            ["\xE2\x80\x93", "\xE2\x80\x94", "\xE2\x88\x92", "\xEF\xB9\x98", "\xEF\xBC\x8D"],
            '-',
            $rawText
        );
        // Normalize non-breaking spaces and zero-width characters
        $rawText = preg_replace('/[\xC2\xA0\xE2\x80\x8B\xE2\x80\x8C\xE2\x80\x8D]/', ' ', $rawText);

        $lines = preg_split('/\r?\n/', $rawText);
        $items = [];
        $skipped = [];

        // Build brand lookup
        $brandLookup = [];
        foreach ($brands as $b) {
            $brandLookup[strtolower($b->name)] = $b->id;
        }
        $appleBrandId = $brandLookup['apple'] ?? null;
        $samsungBrandId = $brandLookup['samsung'] ?? null;

        // Build existing-product lookup by lowercased name (and slug)
        $productLookup = [];
        foreach ($existingProducts as $p) {
            $key = strtolower(trim($p->name));
            $productLookup[$key] = $p;
        }

        // Track parsing context (set by header lines)
        $context = [
            'brand' => null,        // 'apple' | 'samsung' | null
            'condition' => 'new',   // 'new' | 'ex-uk' | 'refurbished'
        ];

        // Patterns for headers
        $isHeader = function ($line) use (&$context, &$skipped) {
            $lower = strtolower(trim($line));
            if ($lower === '') return true; // empty lines are skipped silently

            // Condition header
            if (preg_match('/^(brand\s*new|new\s*stock|new)$/i', $lower)) {
                $context['condition'] = 'new';
                $skipped[] = ['line' => $line, 'reason' => 'Section header (condition: new)'];
                return true;
            }
            // Detect Ex-US vs Ex-UK separately
            if (preg_match('/ex[\s\-]?(us|usa)\b/i', $lower)) {
                $context['condition'] = 'ex-us';
                if (preg_match('/iphone/i', $lower)) $context['brand'] = 'apple';
                if (preg_match('/samsung/i', $lower)) $context['brand'] = 'samsung';
                $skipped[] = ['line' => $line, 'reason' => 'Section header (Ex-US)'];
                return true;
            }
            if (preg_match('/ex[\s\-]?uk\b/i', $lower)) {
                $context['condition'] = 'ex-uk';
                if (preg_match('/iphone/i', $lower)) $context['brand'] = 'apple';
                if (preg_match('/samsung/i', $lower)) $context['brand'] = 'samsung';
                $skipped[] = ['line' => $line, 'reason' => 'Section header (Ex-UK)'];
                return true;
            }
            if (preg_match('/refurb/i', $lower)) {
                $context['condition'] = 'refurbished';
                $skipped[] = ['line' => $line, 'reason' => 'Section header (refurbished)'];
                return true;
            }
            // Brand-only header
            if (preg_match('/^iphones?$/i', $lower)) {
                $context['brand'] = 'apple';
                $skipped[] = ['line' => $line, 'reason' => 'Brand header (iPhone)'];
                return true;
            }
            if (preg_match('/^samsung$/i', $lower)) {
                $context['brand'] = 'samsung';
                $skipped[] = ['line' => $line, 'reason' => 'Brand header (Samsung)'];
                return true;
            }
            return false;
        };

        // Match: <model_part> <storage> [sim] - <price> [(color)]
        // Examples:
        //  17 Pro 256GB eSIM - 162,000 (Silver)
        //  X 64GB - 14,000/-
        //  Xr 64GB - 17,000/-
        //  11 Pro Max 256GB - 37,000/-
        //  S10e 128GB - 15,000/-
        //  S24U 512GB - 83,000/-
        //  Flip 3 128GB - 22,000/-
        //  Fold 5 256GB - 63,000/-
        $linePattern = '/^([A-Za-z0-9][A-Za-z0-9\+\s]*?)\s+(\d+(?:GB|TB))\s*(eSIM|Physical|Dual\s*SIM|Dual)?\s*[-–—]\s*([\d,]+)(?:[\/=\-]*)?\s*(?:\(([^)]+)\))?\s*$/i';

        foreach ($lines as $line) {
            $line = trim($line);
            if ($line === '') continue;

            if ($isHeader($line)) continue;

            if (!preg_match($linePattern, $line, $m)) {
                $skipped[] = ['line' => $line, 'reason' => 'Could not parse format'];
                continue;
            }

            $modelPart = trim($m[1]);
            $storage = strtoupper($m[2]);
            $simType = !empty($m[3]) ? trim($m[3]) : null;
            $rawPriceStr = str_replace(',', '', $m[4]);
            $rawPrice = (int) $rawPriceStr;
            $color = !empty($m[5]) ? trim($m[5]) : null;

            if ($rawPrice <= 0) {
                $skipped[] = ['line' => $line, 'reason' => 'Invalid price'];
                continue;
            }

            // Determine brand from context or model part
            $brand = $context['brand'];
            $brandId = null;
            $brandName = null;

            // Auto-detect brand from model name keywords if context not set
            $modelLower = strtolower($modelPart);
            if (!$brand) {
                if (preg_match('/^(s\d+|note|flip|fold|galaxy|a\d{2})/i', $modelLower)) {
                    $brand = 'samsung';
                } elseif (preg_match('/^(\d+|x[rs]?|se|pro|max|mini)/i', $modelLower)) {
                    // Default to apple for purely numeric or X-series models
                    $brand = 'apple';
                }
            }

            // Build full product name
            $productName = $modelPart;
            if ($brand === 'apple') {
                $brandName = 'Apple';
                $brandId = $appleBrandId;
                // Expand shorthand: "PM" → "Pro Max", "Mini" stays
                $productName = preg_replace('/\bPM\b/i', 'Pro Max', $productName);
                // Prefix iPhone if not already there
                if (!preg_match('/^iphone/i', $productName)) {
                    $productName = 'iPhone ' . $productName;
                }
            } elseif ($brand === 'samsung') {
                $brandName = 'Samsung';
                $brandId = $samsungBrandId;
                // Map common shortcuts
                if (preg_match('/^s\d+u$/i', $modelPart)) {
                    // S24U -> Galaxy S24 Ultra
                    $num = preg_replace('/[^\d]/', '', $modelPart);
                    $productName = "Galaxy S{$num} Ultra";
                } elseif (preg_match('/^s(\d+)\+$/i', $modelPart, $fm)) {
                    // S22+ -> Galaxy S22 Plus
                    $productName = "Galaxy S{$fm[1]} Plus";
                } elseif (preg_match('/^s(\d+)fe$/i', $modelPart, $fm)) {
                    // S20FE -> Galaxy S20 FE
                    $productName = "Galaxy S{$fm[1]} FE";
                } elseif (preg_match('/^note\s*(\d+)/i', $modelPart, $fm)) {
                    // Note 10 -> Galaxy Note 10
                    $productName = "Galaxy Note {$fm[1]}";
                } elseif (preg_match('/^flip\s*(\d+)$/i', $modelPart, $fm)) {
                    $productName = "Galaxy Z Flip {$fm[1]}";
                } elseif (preg_match('/^fold\s*(\d+)$/i', $modelPart, $fm)) {
                    $productName = "Galaxy Z Fold {$fm[1]}";
                } elseif (!preg_match('/^galaxy/i', $productName)) {
                    $productName = 'Galaxy ' . $productName;
                }
            }

            // Apply markup
            $markupResult = $this->applyMarkup($rawPrice);

            // Try matching against existing products
            $matchKey = strtolower(trim($productName));
            $existing = $productLookup[$matchKey] ?? null;
            $action = 'create';
            $existingProductId = null;
            $existingVariantId = null;
            $existingPrice = null;

            if ($existing) {
                $existingProductId = $existing->id;
                // Check for variant with same storage
                $existingVariant = $existing->variants->firstWhere('storage', $storage);
                if ($existingVariant) {
                    $action = 'update';
                    $existingVariantId = $existingVariant->id;
                    $existingPrice = (float) $existingVariant->price;
                } else {
                    $action = 'new_variant';
                }
            }

            $items[] = [
                'action' => $action,
                'product_name' => $productName,
                'storage' => $storage,
                'sim_type' => $simType,
                'color' => $color,
                'raw_price' => $rawPrice,
                'price' => $markupResult['price'],
                'markup_applied' => $markupResult['markup'],
                'condition' => $context['condition'],
                'brand_name' => $brandName,
                'brand_id' => $brandId,
                'category_id' => null,
                'existing_product_id' => $existingProductId,
                'existing_variant_id' => $existingVariantId,
                'existing_price' => $existingPrice,
                'confidence' => $existing ? 'high' : 'medium',
                'notes' => null,
            ];
        }

        return [
            'items' => $items,
            'skipped' => $skipped,
            'summary' => [
                'total_parsed' => count($items),
                'updates' => count(array_filter($items, fn($i) => $i['action'] === 'update')),
                'new_variants' => count(array_filter($items, fn($i) => $i['action'] === 'new_variant')),
                'creates' => count(array_filter($items, fn($i) => $i['action'] === 'create')),
                'skipped' => count($skipped),
            ],
        ];
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
            'items.*.sim_type' => 'nullable|string',
            'items.*.color' => 'nullable|string',
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
            // Only NEWLY created products get AI enrichment (images, descriptions,
            // specs, advantages). Updates and new-variants on existing products
            // are skipped because they already have content.
            $newProductIds = [];

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
                                    'sim_type' => $item['sim_type'] ?? $variant->sim_type,
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
                                $simSlug = !empty($item['sim_type']) ? '-' . Str::slug($item['sim_type']) : '';
                                $sku = Str::slug($product->name) . '-' . Str::slug($item['storage'] ?? 'default') . $simSlug;
                                // Match on both storage AND sim_type so eSIM and Physical are separate variants
                                $variant = $product->variants()->updateOrCreate(
                                    [
                                        'storage' => $item['storage'] ?? null,
                                        'sim_type' => $item['sim_type'] ?? null,
                                    ],
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

                        $simSlug = !empty($item['sim_type']) ? '-' . Str::slug($item['sim_type']) : '';
                        $variantSku = Str::slug($item['product_name']) . '-' . Str::slug($item['storage'] ?? 'default') . $simSlug;
                        $variant = $product->variants()->updateOrCreate(
                            [
                                'storage' => $item['storage'] ?? null,
                                'sim_type' => $item['sim_type'] ?? null,
                            ],
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
                        // Only flag for AI enrichment if this was a brand-new product row
                        // (updateOrCreate may have matched an existing slug from a prior run)
                        if ($product->wasRecentlyCreated) {
                            $newProductIds[] = $product->id;
                        }
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

            // Build the success message from DB stats
            $msg = [];
            if ($stats['updated'] > 0) $msg[] = "{$stats['updated']} prices updated";
            if ($stats['new_variants'] > 0) $msg[] = "{$stats['new_variants']} new variants added";
            if ($stats['created'] > 0) $msg[] = "{$stats['created']} new products created";
            if ($stats['marked_out'] > 0) $msg[] = "{$stats['marked_out']} products marked out of stock";

            // Dispatch image fetching + AI content generation as a background job
            // (runs AFTER the HTTP response is sent, so nginx never sees a 504).
            // ONLY brand-new products get enriched — existing ones that just had
            // a price update or new variant added already have their content.
            $newProductIds = array_unique($newProductIds);
            if (!empty($newProductIds)) {
                EnrichProductsJob::dispatchAfterResponse($newProductIds);
                $msg[] = count($newProductIds) . ' new products queued for image & AI content enrichment (processing in background)';
            }

            return redirect()->back()->with('success', 'Bulk upload completed: ' . implode(', ', $msg) . '.');
        } catch (\Throwable $th) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Bulk upload failed: ' . $th->getMessage());
        }
    }
}
