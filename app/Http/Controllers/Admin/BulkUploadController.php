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

        $lineCount = substr_count($validated['raw_text'], "\n") + 1;

        // If no API key, use the regex fallback parser
        if (empty($apiKey)) {
            $fallback = $this->fallbackParse($validated['raw_text'], $existingProducts, $brands);
            $fallback['source'] = 'regex';
            $fallback['notice'] = 'AI parser unavailable (no API key). Used regex fallback parser.';

            if (empty($fallback['items'])) {
                return response()->json([
                    'error' => 'Could not parse any products. Check format: expected lines like "iPhone 14 Pro 256GB - 85,000" or "Samsung S24 128GB - 80,000". Skipped: ' . count($fallback['skipped'] ?? []) . ' lines.',
                    'items' => [],
                    'skipped' => $fallback['skipped'] ?? [],
                ], 422);
            }

            return response()->json($fallback);
        }

        // ============================================================
        // OPTIMIZED AI PATH — batch + parallel + slim context
        // ============================================================

        // 1. SLIM product context: only fields AI needs for matching.
        //    Drop variants, slug, category_id. Save ~70% input tokens.
        $productContext = $existingProducts->map(fn($p) => [
            'id' => $p->id,
            'name' => $p->name,
            'brand' => $p->brand?->name,
        ])->take(200)->toArray(); // Cap at 200 products (enough context)

        $brandList = $brands->map(fn($b) => ['id' => $b->id, 'name' => $b->name])->toArray();

        // 2. BATCH the input into chunks of 15 lines each.
        //    Keep header/context lines (Ex US, iPhones, Samsung) in EVERY
        //    batch so AI knows the brand/condition context.
        $lines = preg_split('/\r?\n/', $validated['raw_text']);
        $batches = $this->splitIntoBatches($lines, 15);

        if (empty($batches)) {
            $fallback = $this->fallbackParse($validated['raw_text'], $existingProducts, $brands);
            $fallback['source'] = 'regex';
            $fallback['notice'] = 'No parseable lines found. Used regex fallback.';
            return response()->json($fallback);
        }

        // 3. PARALLEL requests via Http::pool() — all batches fire at once.
        //    Each batch has <=15 items, small prompt, quick response.
        try {
            $model = env('ANTHROPIC_MODEL', 'claude-sonnet-4-5');

            $responses = Http::pool(function ($pool) use ($batches, $productContext, $brandList, $apiKey, $model) {
                return array_map(function ($batch) use ($pool, $productContext, $brandList, $apiKey, $model) {
                    $prompt = $this->buildBatchPrompt($batch, $productContext, $brandList);
                    return $pool->withHeaders([
                        'x-api-key' => $apiKey,
                        'anthropic-version' => '2023-06-01',
                        'content-type' => 'application/json',
                    ])->timeout(60)->post('https://api.anthropic.com/v1/messages', [
                        'model' => $model,
                        'max_tokens' => 4096,
                        'messages' => [['role' => 'user', 'content' => $prompt]],
                    ]);
                }, $batches);
            });

            // 4. MERGE results — handle both Response objects AND exceptions
            //    (Http::pool returns Throwable when a request fails)
            $allItems = [];
            $allSkipped = [];
            $failedBatches = 0;
            $failureDetails = [];

            foreach ($responses as $i => $response) {
                // Guard against exception-type responses from pool failures
                if ($response instanceof \Throwable) {
                    $failedBatches++;
                    $failureDetails[] = "Batch " . ($i + 1) . ": " . $response->getMessage();
                    continue;
                }
                if (!is_object($response) || !method_exists($response, 'successful')) {
                    $failedBatches++;
                    $failureDetails[] = "Batch " . ($i + 1) . ": invalid response";
                    continue;
                }
                if (!$response->successful()) {
                    $failedBatches++;
                    $errMsg = $response->json('error.message') ?? 'HTTP ' . $response->status();
                    $failureDetails[] = "Batch " . ($i + 1) . ": " . $errMsg;
                    continue;
                }
                $aiText = $response->json('content.0.text', '');
                $parsed = $this->extractJson($aiText);
                if ($parsed === null) {
                    $failedBatches++;
                    $failureDetails[] = "Batch " . ($i + 1) . ": invalid JSON response";
                    continue;
                }
                $allItems = array_merge($allItems, $parsed['items'] ?? []);
                $allSkipped = array_merge($allSkipped, $parsed['skipped'] ?? []);
            }

            Log::info('Bulk upload AI batched', [
                'batches' => count($batches),
                'items' => count($allItems),
                'failed_batches' => $failedBatches,
                'failure_details' => $failureDetails,
            ]);

            // 5. If any items came back, return them (even partial success)
            if (!empty($allItems)) {
                $notice = null;
                if ($failedBatches > 0) {
                    $notice = "AI parsed " . count($allItems) . " items across " . count($batches) . " batches ({$failedBatches} batch(es) failed and were skipped).";
                } else {
                    $notice = "AI parsed " . count($allItems) . " items across " . count($batches) . " parallel batches.";
                }

                // Backfill category_id on any AI items that don't have one
                foreach ($allItems as &$item) {
                    if (empty($item['category_id']) && !empty($item['product_name'])) {
                        $item['category_id'] = $this->inferCategoryId($item['product_name']);
                    }
                }

                return response()->json([
                    'items' => $allItems,
                    'skipped' => $allSkipped,
                    'summary' => [
                        'total_parsed' => count($allItems),
                        'updates' => count(array_filter($allItems, fn($i) => ($i['action'] ?? '') === 'update')),
                        'new_variants' => count(array_filter($allItems, fn($i) => ($i['action'] ?? '') === 'new_variant')),
                        'creates' => count(array_filter($allItems, fn($i) => ($i['action'] ?? '') === 'create')),
                        'skipped' => count($allSkipped),
                    ],
                    'source' => 'ai',
                    'notice' => $notice,
                    'batches' => count($batches),
                ]);
            }

            $aiFailureReason = "All {$failedBatches} AI batches failed. Using regex fallback.";
        } catch (\Exception $e) {
            $aiFailureReason = 'AI parallel request failed: ' . $e->getMessage();
        }

        // AI couldn't do it — fall back to regex
        $fallback = $this->fallbackParse($validated['raw_text'], $existingProducts, $brands);
        $fallback['source'] = 'regex';
        $fallback['notice'] = $aiFailureReason;

        if (empty($fallback['items'])) {
            return response()->json([
                'error' => $aiFailureReason . ' Regex fallback also found no products. Check your input format.',
                'items' => [],
                'skipped' => $fallback['skipped'] ?? [],
            ], 422);
        }

        return response()->json($fallback);
    }

    /**
     * Split lines into batches. Groups items BY section first (so each
     * section's items stay together), then chunks each section into
     * batches of N items, prepending the section's header to each batch.
     */
    private function splitIntoBatches(array $lines, int $batchSize): array
    {
        // Walk through lines, build sections: each section has its own
        // header block + items belonging to it.
        $sections = [];
        $currentHeaders = [];
        $currentItems = [];

        $flushSection = function () use (&$sections, &$currentHeaders, &$currentItems) {
            if (!empty($currentItems)) {
                $sections[] = [
                    'headers' => $currentHeaders,
                    'items' => $currentItems,
                ];
            }
            $currentItems = [];
        };

        foreach ($lines as $line) {
            $trim = trim($line);
            if ($trim === '') continue;

            // Data line?
            if (preg_match('/\d+\s*(GB|TB).*\d/i', $trim)) {
                $currentItems[] = $line;
                continue;
            }

            // Section-starting header (brand/condition change)?
            $lower = strtolower($trim);
            $isSectionHeader = preg_match('/^(brand\s*new|new\s*stock|ex[\s\-]?(us|uk|usa)|refurb)/i', $lower)
                || preg_match('/^(iphones?|samsung)$/i', $lower);

            if ($isSectionHeader) {
                // Close previous section before starting new one
                $flushSection();
                $currentHeaders = [$line];
            } else {
                // Regular descriptive line — add to current header block
                $currentHeaders[] = $line;
            }
        }
        // Don't forget the final section
        $flushSection();

        if (empty($sections)) return [];

        // Now chunk each section's items into batches of $batchSize
        $batches = [];
        foreach ($sections as $section) {
            $chunks = array_chunk($section['items'], $batchSize);
            foreach ($chunks as $chunk) {
                $batches[] = implode("\n", array_merge($section['headers'], [''], $chunk));
            }
        }
        return $batches;
    }

    /**
     * Slim prompt for a single batch — no product catalog JSON, just the
     * essentials. Asks for compact response (fewer fields per item).
     */
    private function buildBatchPrompt(string $batchText, array $existingProducts, array $brands): string
    {
        $brandsJson = json_encode($brands);
        // Only send top 50 existing products by name match probability
        $productsJson = json_encode(array_slice($existingProducts, 0, 50));

        return <<<PROMPT
Parse this Kenyan phone/laptop price list. Output ONLY compact JSON.

## Existing products (for match detection):
{$productsJson}

## Brands:
{$brandsJson}

## Markup rules (apply to EVERY raw price to get final selling price):
- 15,000–20,000 → +1,500
- 20,000–30,000 → +2,500
- 30,000–40,000 → +3,000
- 40,000–50,000 → +4,000
- 50,000–55,000 → +4,500
- 55,000–88,000 → +5,000
- 89,000+ → +6,000
- Below 15,000 → no markup

## Price list:
{$batchText}

## Rules:
- "Ex US"/"Ex USA" header → condition: "ex-us"
- "Ex UK" header → condition: "ex-uk"
- "Brand New" or no header → condition: "new"
- "Refurbished" → condition: "refurbished"
- eSIM keyword → sim_type: "eSIM"
- Match against existing products by name. If exact name+storage match → action: "update" with existing_product_id and existing_variant_id. If name matches but not storage → "new_variant". Else → "create".

## Output (JSON only, no markdown):
{
  "items": [
    {
      "action": "update|new_variant|create",
      "product_name": "Full product name",
      "storage": "256GB",
      "sim_type": "eSIM or null",
      "raw_price": 25000,
      "price": 27500,
      "condition": "new|ex-uk|ex-us|refurbished",
      "brand_id": 1,
      "existing_product_id": null,
      "existing_variant_id": null
    }
  ],
  "skipped": []
}
PROMPT;
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
    /**
     * Infer a sensible category_id from the product name.
     * Looks for an existing category first. If none matches, creates the
     * category on the fly so the product has a proper home.
     */
    private function inferCategoryId(string $productName): ?int
    {
        static $cache = null;
        if ($cache === null) {
            $cache = Category::where('is_active', true)->get(['id', 'name', 'slug'])->keyBy(fn($c) => strtolower($c->slug));
        }

        $name = strtolower($productName);

        // Product-type keyword → preferred category slug + display name
        // First slug in each list is what we'll CREATE if no match found.
        $rules = [
            [
                'keywords' => ['iphone', 'samsung', 'pixel', 'galaxy', 'redmi', 'oppo', 'nokia', 'infinix', 'tecno', 'xiaomi', 'realme', 'honor'],
                'slugs' => ['phones', 'smartphones', 'mobile-phones'],
                'createName' => 'Phones',
            ],
            [
                'keywords' => ['macbook', 'laptop', 'thinkpad', 'zenbook', 'ideapad'],
                'slugs' => ['laptops', 'computers'],
                'createName' => 'Laptops',
            ],
            [
                'keywords' => ['ipad', 'tab '],
                'slugs' => ['tablets'],
                'createName' => 'Tablets',
            ],
            [
                'keywords' => ['airpod', 'earbud', 'headphone', 'headset'],
                'slugs' => ['accessories', 'audio'],
                'createName' => 'Accessories',
            ],
            [
                'keywords' => ['watch'],
                'slugs' => ['wearables', 'accessories'],
                'createName' => 'Wearables',
            ],
        ];

        foreach ($rules as $rule) {
            foreach ($rule['keywords'] as $kw) {
                if (str_contains($name, $kw)) {
                    // Try to find an existing category in the preferred order
                    foreach ($rule['slugs'] as $slug) {
                        if (isset($cache[$slug])) return $cache[$slug]->id;
                    }
                    // Nothing matched — create the preferred category
                    $newCat = Category::firstOrCreate(
                        ['slug' => $rule['slugs'][0]],
                        [
                            'name' => $rule['createName'],
                            'is_active' => true,
                            'sort_order' => 0,
                        ]
                    );
                    // Refresh cache so repeat lookups find it
                    $cache->put(strtolower($newCat->slug), $newCat);
                    return $newCat->id;
                }
            }
        }

        // No rule matched at all — create a generic "Uncategorized" bucket
        $fallback = Category::firstOrCreate(
            ['slug' => 'uncategorized'],
            [
                'name' => 'Uncategorized',
                'is_active' => true,
                'sort_order' => 999,
            ]
        );
        $cache->put('uncategorized', $fallback);
        return $fallback->id;
    }

    /**
     * Generate a globally-unique variant SKU.
     *
     * product_variants.sku has a UNIQUE constraint. If the generated SKU
     * already belongs to the SAME variant we're about to update (matched
     * on product_id + storage + sim_type), we reuse it. Otherwise we
     * append -2, -3, ... until a free SKU is found.
     */
    private function uniqueVariantSku(string $baseSku, int $productId, ?string $storage, ?string $simType): string
    {
        // Find the single existing variant (if any) using this SKU
        $existing = ProductVariant::where('sku', $baseSku)->first();

        // No one owns this SKU → we can use it freely
        if (!$existing) return $baseSku;

        // The existing variant IS the one we're about to update → reuse
        if ($existing->product_id === $productId
            && $existing->storage === $storage
            && $existing->sim_type === $simType) {
            return $baseSku;
        }

        // Someone else owns this SKU → find next available suffix
        $i = 2;
        while (ProductVariant::where('sku', $baseSku . '-' . $i)->exists()) {
            $i++;
            if ($i > 999) {
                // Astronomical edge case — fall back to random suffix
                return $baseSku . '-' . substr(md5(uniqid()), 0, 6);
            }
        }
        return $baseSku . '-' . $i;
    }

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
                'category_id' => $this->inferCategoryId($productName),
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
                                $sku = $this->uniqueVariantSku(
                                    Str::slug($product->name) . '-' . Str::slug($item['storage'] ?? 'default') . $simSlug,
                                    $product->id,
                                    $item['storage'] ?? null,
                                    $item['sim_type'] ?? null
                                );
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

                        // Ensure category_id is set — DB requires it.
                        // Infer from the product name if the item didn't specify one.
                        $categoryId = $item['category_id'] ?? $this->inferCategoryId($item['product_name']);

                        $product = Product::updateOrCreate(
                            ['slug' => $slug],
                            [
                                'name' => $item['product_name'],
                                'base_sku' => $baseSku,
                                'brand_id' => $item['brand_id'] ?? null,
                                'category_id' => $categoryId,
                                'base_price' => $item['price'],
                                'cost_price' => $item['raw_price'] ?? null,
                                'condition' => $item['condition'] ?? 'new',
                                'stock_status' => 'in_stock',
                                'is_active' => true,
                            ]
                        );

                        $simSlug = !empty($item['sim_type']) ? '-' . Str::slug($item['sim_type']) : '';
                        $variantSku = $this->uniqueVariantSku(
                            Str::slug($item['product_name']) . '-' . Str::slug($item['storage'] ?? 'default') . $simSlug,
                            $product->id,
                            $item['storage'] ?? null,
                            $item['sim_type'] ?? null
                        );
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
