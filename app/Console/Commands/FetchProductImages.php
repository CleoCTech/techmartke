<?php

namespace App\Console\Commands;

use App\Models\Product;
use App\Models\ProductImage;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;

class FetchProductImages extends Command
{
    protected $signature = 'products:fetch-images {--limit=10 : Number of products to process} {--force : Replace existing placeholder images}';
    protected $description = 'Fetch real product images using AI for products with placeholder or missing images';

    public function handle()
    {
        $limit = (int) $this->option('limit');
        $force = $this->option('force');

        $apiKey = config('anthropic.api_key') ?: env('ANTHROPIC_API_KEY');
        if (empty($apiKey)) {
            $this->error('ANTHROPIC_API_KEY is not set in .env');
            return 1;
        }

        // Find products that need images
        $query = Product::query();
        if ($force) {
            // Include products with placeholder images
            $query->where(function ($q) {
                $q->doesntHave('images')
                  ->orWhereHas('images', function ($q2) {
                      $q2->where('image_url', 'like', '%placehold.co%');
                  });
            });
        } else {
            $query->doesntHave('images');
        }

        $products = $query->limit($limit)->get();

        if ($products->isEmpty()) {
            $this->info('No products need images.');
            return 0;
        }

        $this->info("Processing {$products->count()} products...");
        $bar = $this->output->createProgressBar($products->count());
        $success = 0;

        foreach ($products as $product) {
            $bar->advance();

            try {
                $response = Http::withHeaders([
                    'x-api-key' => $apiKey,
                    'anthropic-version' => '2023-06-01',
                    'content-type' => 'application/json',
                ])->timeout(30)->post('https://api.anthropic.com/v1/messages', [
                    'model' => env('ANTHROPIC_MODEL', 'claude-sonnet-4-5'),
                    'max_tokens' => 300,
                    'messages' => [
                        ['role' => 'user', 'content' => $this->buildPrompt($product->name)],
                    ],
                ]);

                if (!$response->successful()) {
                    $this->newLine();
                    $this->warn("  Failed for {$product->name}: API error");
                    continue;
                }

                $aiText = trim($response->json('content.0.text', ''));
                $imageUrl = $this->extractUrl($aiText);

                if ($imageUrl && !str_contains($imageUrl, 'placehold.co')) {
                    // Verify the URL actually loads
                    $check = Http::timeout(10)->head($imageUrl);
                    if ($check->successful()) {
                        // Delete old placeholder image
                        $product->images()->where('image_url', 'like', '%placehold.co%')->delete();

                        ProductImage::updateOrCreate(
                            ['product_id' => $product->id, 'is_primary' => true],
                            [
                                'image_url' => $imageUrl,
                                'alt_text' => $product->name,
                                'image_type' => 'main',
                                'sort_order' => 0,
                            ]
                        );
                        $success++;
                    } else {
                        $this->newLine();
                        $this->warn("  URL 404 for {$product->name}: {$imageUrl}");
                    }
                }

                usleep(300000); // 0.3s delay between requests
            } catch (\Exception $e) {
                $this->newLine();
                $this->warn("  Error for {$product->name}: {$e->getMessage()}");
            }
        }

        $bar->finish();
        $this->newLine(2);
        $this->info("Done! {$success}/{$products->count()} images fetched successfully.");

        return 0;
    }

    private function buildPrompt(string $productName): string
    {
        return <<<PROMPT
Find a publicly accessible, hotlinkable product image URL for: "{$productName}"

Requirements:
- Must be a DIRECT image URL ending in .jpg, .png, or .webp
- Must return HTTP 200 when accessed (no auth required)
- Clean product photo preferred (white background)
- Must NOT be from gsmarena.com (blocks hotlinking)

Good sources:
- Apple CDN: store.storeimages.cdn-apple.com
- Samsung CDN: image-us.samsung.com or images.samsung.com
- Amazon product images: m.media-amazon.com
- Best Buy images: pisces.bbystatic.com

Return ONLY the direct image URL, nothing else.
PROMPT;
    }

    private function extractUrl(string $text): ?string
    {
        $text = trim($text);
        if (filter_var($text, FILTER_VALIDATE_URL)) {
            return $text;
        }
        if (preg_match('/(https?:\/\/[^\s\'"<>]+)/', $text, $matches)) {
            return $matches[1];
        }
        return null;
    }
}
