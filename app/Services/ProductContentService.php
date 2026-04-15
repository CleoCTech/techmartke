<?php

namespace App\Services;

use App\Models\Product;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class ProductContentService
{
    /**
     * Generate AI content for a single product if it's missing descriptions/specs/advantages.
     */
    public static function generateForProduct(Product $product): bool
    {
        // Check if content already exists
        $hasDescription = !empty($product->description);
        $hasSpecs = $product->specifications()->count() > 0;
        $hasAdvantages = $product->advantages()->count() > 0;

        // Skip if everything already exists
        if ($hasDescription && $hasSpecs && $hasAdvantages) {
            return false;
        }

        $apiKey = config('anthropic.api_key') ?: env('ANTHROPIC_API_KEY');
        if (empty($apiKey)) {
            return false;
        }

        $brandName = $product->brand?->name ?? '';
        $categoryName = $product->category?->name ?? '';
        $condition = $product->condition ?? 'new';
        $price = $product->base_price ?? 0;
        $variants = $product->variants->map(fn($v) => trim(($v->storage ?? '') . ' ' . ($v->color ?? '')))->filter()->implode(', ');

        // Only request what's missing
        $fieldsNeeded = [];
        if (!$hasDescription) {
            $fieldsNeeded[] = '"short_description": "A compelling 1-2 sentence product summary (max 150 chars)."';
            $fieldsNeeded[] = '"description": "A detailed 3-5 paragraph product description for Kenyan buyers. If Ex-UK/Ex-US, mention quality tested."';
            $fieldsNeeded[] = '"meta_title": "SEO page title (max 60 chars). Format: Product Name - Price | TechMart KE"';
            $fieldsNeeded[] = '"meta_description": "SEO meta description (max 155 chars) with call to action."';
        }
        if (!$hasSpecs) {
            $fieldsNeeded[] = '"specifications": [{"spec_group": "Group", "spec_name": "Name", "spec_value": "Value"}] — generate 8-15 realistic specs grouped by Display, Performance, Camera, Battery, Storage, Connectivity, Design. Use accurate values if you know this product.';
        }
        if (!$hasAdvantages) {
            $fieldsNeeded[] = '"advantages": ["point 1", "point 2"] — generate 4-6 selling points for Kenyan buyers.';
        }

        $fieldsStr = implode(",\n  ", $fieldsNeeded);

        $prompt = <<<PROMPT
You are a product copywriter for TechMart KE, a phone/computer store in Nairobi, Kenya. Prices in KES.

Product: {$product->name}
Brand: {$brandName}
Category: {$categoryName}
Condition: {$condition}
Price: KSh {$price}
Variants: {$variants}

Generate ONLY the following missing fields as a JSON object (no markdown, no explanation):
{
  {$fieldsStr}
}
PROMPT;

        try {
            $response = Http::withHeaders([
                'x-api-key' => $apiKey,
                'anthropic-version' => '2023-06-01',
                'content-type' => 'application/json',
            ])->timeout(45)->post('https://api.anthropic.com/v1/messages', [
                'model' => env('ANTHROPIC_MODEL', 'claude-sonnet-4-5'),
                'max_tokens' => 2048,
                'messages' => [['role' => 'user', 'content' => $prompt]],
            ]);

            if (!$response->successful()) {
                Log::warning('ProductContentService AI error for product ' . $product->id, [
                    'error' => $response->json('error.message'),
                ]);
                return false;
            }

            $text = $response->json('content.0.text', '');
            $data = json_decode($text, true);
            if (!$data && preg_match('/\{[\s\S]*\}/', $text, $m)) {
                $data = json_decode($m[0], true);
            }

            if (!$data) {
                Log::warning('ProductContentService: failed to parse AI response for product ' . $product->id);
                return false;
            }

            // Update descriptions if missing
            if (!$hasDescription) {
                $product->update(array_filter([
                    'short_description' => $data['short_description'] ?? null,
                    'description' => $data['description'] ?? null,
                    'meta_title' => $data['meta_title'] ?? null,
                    'meta_description' => $data['meta_description'] ?? null,
                ]));
            }

            // Create specifications if missing
            if (!$hasSpecs && !empty($data['specifications'])) {
                foreach ($data['specifications'] as $spec) {
                    if (!empty($spec['spec_name'])) {
                        $product->specifications()->create([
                            'spec_group' => $spec['spec_group'] ?? 'General',
                            'spec_name' => $spec['spec_name'],
                            'spec_value' => $spec['spec_value'] ?? '',
                        ]);
                    }
                }
            }

            // Create advantages if missing
            if (!$hasAdvantages && !empty($data['advantages'])) {
                foreach ($data['advantages'] as $adv) {
                    if (!empty($adv)) {
                        $product->advantages()->create(['advantage' => $adv]);
                    }
                }
            }

            return true;
        } catch (\Exception $e) {
            Log::error('ProductContentService error for product ' . $product->id . ': ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Generate content for multiple products that are missing it.
     * Returns the count of products enriched.
     */
    public static function generateMissingContent(array $productIds): int
    {
        $count = 0;
        $products = Product::with(['brand', 'category', 'variants', 'specifications', 'advantages'])
            ->whereIn('id', $productIds)
            ->where(function ($q) {
                $q->whereNull('description')
                  ->orWhere('description', '')
                  ->orWhereDoesntHave('specifications')
                  ->orWhereDoesntHave('advantages');
            })
            ->get();

        foreach ($products as $product) {
            if (self::generateForProduct($product)) {
                $count++;
            }
        }

        return $count;
    }
}
