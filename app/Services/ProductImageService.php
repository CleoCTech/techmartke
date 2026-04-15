<?php

namespace App\Services;

use App\Models\Product;
use App\Models\ProductImage;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class ProductImageService
{
    /**
     * Find and attach an image for a product using AI to determine the best GSMArena URL.
     */
    public static function fetchAndAttachImage(Product $product): ?string
    {
        // Skip if product already has images
        if ($product->images()->count() > 0) {
            return $product->images()->first()->image_url;
        }

        $apiKey = config('anthropic.api_key') ?: env('ANTHROPIC_API_KEY');
        if (empty($apiKey)) {
            return null;
        }

        try {
            $response = Http::withHeaders([
                'x-api-key' => $apiKey,
                'anthropic-version' => '2023-06-01',
                'content-type' => 'application/json',
            ])->timeout(30)->post('https://api.anthropic.com/v1/messages', [
                'model' => env('ANTHROPIC_MODEL', 'claude-sonnet-4-5'),
                'max_tokens' => 256,
                'messages' => [
                    ['role' => 'user', 'content' => self::buildImagePrompt($product->name)],
                ],
            ]);

            if (!$response->successful()) {
                Log::warning('AI image fetch failed for: ' . $product->name);
                return null;
            }

            $aiText = trim($response->json('content.0.text', ''));
            $imageUrl = self::extractUrl($aiText);

            if ($imageUrl) {
                ProductImage::create([
                    'product_id' => $product->id,
                    'image_url' => $imageUrl,
                    'alt_text' => $product->name,
                    'is_primary' => true,
                    'image_type' => 'main',
                    'sort_order' => 0,
                ]);
                return $imageUrl;
            }
        } catch (\Exception $e) {
            Log::warning('AI image fetch error for ' . $product->name . ': ' . $e->getMessage());
        }

        return null;
    }

    private static function buildImagePrompt(string $productName): string
    {
        return <<<PROMPT
I need a publicly accessible product image URL for: "{$productName}"

Find a direct image URL from one of these reliable sources (in order of preference):
1. Apple.com CDN for iPhones/MacBooks (e.g. https://store.storeimages.cdn-apple.com/...)
2. Samsung.com CDN for Samsung phones
3. Any other reliable, publicly hotlinkable product image CDN

The image must:
- Be a direct .jpg or .png URL that loads in a browser
- Be a clean product photo (white/transparent background preferred)
- NOT be from GSMArena (they block hotlinking)
- NOT require authentication

If you cannot find a reliable direct image URL, return this placeholder:
https://placehold.co/400x400/f1f5f9/475569?text={URL-encoded-product-name}

Respond with ONLY the URL, nothing else.
PROMPT;
    }

    private static function extractUrl(string $text): ?string
    {
        $text = trim($text);
        // Direct URL
        if (filter_var($text, FILTER_VALIDATE_URL)) {
            return $text;
        }
        // Extract URL from text
        if (preg_match('/(https?:\/\/[^\s\'"<>]+)/', $text, $matches)) {
            return $matches[1];
        }
        return null;
    }

    /**
     * Fetch images for multiple products that don't have images yet.
     * Used after bulk upload.
     */
    public static function fetchMissingImages(array $productIds): int
    {
        $count = 0;
        $products = Product::whereIn('id', $productIds)
            ->doesntHave('images')
            ->get();

        foreach ($products as $product) {
            if (self::fetchAndAttachImage($product)) {
                $count++;
            }
            // Small delay to avoid rate limiting
            if ($count > 0 && $count % 5 === 0) {
                usleep(500000); // 0.5s
            }
        }
        return $count;
    }
}
