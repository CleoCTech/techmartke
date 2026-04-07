<?php

namespace App\Jobs;

use App\Services\ProductContentService;
use App\Services\ProductImageService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

/**
 * Background job that enriches newly bulk-uploaded products with:
 *   1. Product images (fetched from web search / placeholder service)
 *   2. AI-generated descriptions, specs, advantages, SEO meta
 *
 * Dispatched via dispatchAfterResponse() from BulkUploadController so the
 * HTTP request returns immediately and nginx never sees a 504. Even with
 * QUEUE_CONNECTION=sync, "after response" runs once the response has been
 * flushed to the browser.
 */
class EnrichProductsJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $timeout = 600; // 10 minutes for large batches
    public int $tries = 1;

    public function __construct(public array $productIds)
    {
    }

    public function handle(): void
    {
        if (empty($this->productIds)) {
            return;
        }

        $ids = array_unique($this->productIds);
        Log::info('EnrichProductsJob: starting', ['count' => count($ids)]);

        // 1. Fetch missing product images
        try {
            $imagesAdded = ProductImageService::fetchMissingImages($ids);
            Log::info('EnrichProductsJob: images fetched', ['added' => $imagesAdded]);
        } catch (\Throwable $e) {
            Log::error('EnrichProductsJob: image fetch failed', ['error' => $e->getMessage()]);
        }

        // 2. Generate AI descriptions, specs, advantages, SEO meta
        try {
            $contentGenerated = ProductContentService::generateMissingContent($ids);
            Log::info('EnrichProductsJob: content generated', ['enriched' => $contentGenerated]);
        } catch (\Throwable $e) {
            Log::error('EnrichProductsJob: content generation failed', ['error' => $e->getMessage()]);
        }
    }
}
