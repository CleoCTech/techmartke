<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\SearchLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Inertia\Inertia;

class ComparisonController extends Controller
{
    public function compare(Request $request)
    {
        $budget = $request->input('budget');
        $query = $request->input('q');

        // If natural language query, use AI to parse intent
        if ($query && !$budget) {
            return $this->smartSearch($query);
        }

        // Budget-based search (original flow)
        if ($budget) {
            $budget = (float) $budget;
            $minPrice = $budget * 0.7;
            $maxPrice = $budget * 1.1;

            $products = Product::with(['brand', 'category', 'variants', 'images', 'specifications', 'advantages'])
                ->where('is_active', true)
                ->inBudget($minPrice, $maxPrice)
                ->orderByRaw("CASE WHEN stock_status = 'in_stock' THEN 0 ELSE 1 END")
                ->orderBy('base_price')
                ->limit(8)
                ->get();

            $recommendation = $this->getAIRecommendation($products, "budget of KSh " . number_format($budget));

            $this->logSearch($request, "Budget: KSh " . number_format($budget), 'budget', $budget, $products);

            return Inertia::render('Customer/Comparison', [
                'products' => $products,
                'query' => "Budget: KSh " . number_format($budget),
                'budget' => $budget,
                'recommendation' => $recommendation,
                'searchType' => 'budget',
            ]);
        }

        return redirect('/');
    }

    private function smartSearch(string $query)
    {
        // Get all products for AI context (including out of stock)
        $allProducts = Product::with(['brand', 'category', 'variants'])
            ->where('is_active', true)
            ->orderBy('base_price')
            ->get();

        $productList = $allProducts->map(fn($p) => [
            'id' => $p->id,
            'name' => $p->name,
            'brand' => $p->brand?->name,
            'category' => $p->category?->name,
            'condition' => $p->condition,
            'price' => (float) $p->base_price,
            'stock_status' => $p->stock_status,
            'variants' => $p->variants->pluck('storage')->filter()->implode(', '),
        ])->toArray();

        $apiKey = config('anthropic.api_key') ?: env('ANTHROPIC_API_KEY');
        $matchedIds = [];

        if ($apiKey) {
            try {
                $productsJson = json_encode($productList);
                $prompt = <<<PROMPT
You are a smart product search engine for TechMart KE, a general electronics store in Nairobi, Kenya that sells ALL brands of phones, laptops, tablets and accessories (Samsung, Apple/iPhone, Google Pixel, Huawei, MacBook, HP, Dell, Lenovo, etc.). Prices are in KES.

## All Products in Store
{$productsJson}

Note: Products have a "stock_status" field. "in_stock" means available now. "out_of_stock" means temporarily unavailable but we can restock within about a week.

## User's Search Query
"{$query}"

## Instructions
Analyze the user's query to understand their intent:
- Budget constraints (e.g. "under 30K", "around 50,000", "between 20K and 40K")
- Brand preferences (e.g. "Samsung", "iPhone", "MacBook")
- Category preferences (e.g. "phone", "laptop", "tablet")
- Feature requirements (e.g. "good camera", "for gaming", "for school work", "long battery")
- Condition preference (e.g. "new", "ex-uk")

Select the BEST matching products (up to 8). Include BOTH in-stock and out-of-stock products if they match the query. Prioritize in-stock items first, then out-of-stock ones. Always try to return some results — even if not a perfect match, suggest the closest alternatives.

IMPORTANT: TechMart KE sells ALL brands. Never say "we specialize in" any single brand. If a specific product is out of stock, mention it's temporarily unavailable and can be restocked within about a week.

Respond with ONLY JSON (no markdown):
{
  "matched_ids": [1, 2, 3],
  "interpretation": "Brief description of what the user is looking for",
  "recommendation": "A helpful 2-3 sentence recommendation. Speak directly to the customer. If showing out-of-stock items, mention they can be available within about a week. Keep it friendly and conversational for a Kenyan audience."
}
PROMPT;

                $response = Http::withHeaders([
                    'x-api-key' => $apiKey,
                    'anthropic-version' => '2023-06-01',
                    'content-type' => 'application/json',
                ])->timeout(20)->post('https://api.anthropic.com/v1/messages', [
                    'model' => 'claude-sonnet-4-20250514',
                    'max_tokens' => 512,
                    'messages' => [['role' => 'user', 'content' => $prompt]],
                ]);

                if ($response->successful()) {
                    $text = $response->json('content.0.text', '');
                    $data = json_decode($text, true);
                    if (!$data && preg_match('/\{[\s\S]*\}/', $text, $m)) {
                        $data = json_decode($m[0], true);
                    }

                    if ($data) {
                        $matchedIds = $data['matched_ids'] ?? [];
                        $interpretation = $data['interpretation'] ?? '';
                        $recommendation = $data['recommendation'] ?? '';

                        $products = Product::with(['brand', 'category', 'variants', 'images', 'specifications', 'advantages'])
                            ->whereIn('id', $matchedIds)
                            ->where('is_active', true)
                            ->get()
                            ->sortBy(function ($p) use ($matchedIds) {
                                return array_search($p->id, $matchedIds);
                            })->values();

                        $this->logSearch(request(), $query, 'smart', null, $products);

                        return Inertia::render('Customer/Comparison', [
                            'products' => $products,
                            'query' => $query,
                            'interpretation' => $interpretation,
                            'recommendation' => $recommendation,
                            'searchType' => 'smart',
                        ]);
                    }
                }
            } catch (\Exception $e) {
                // Fall through to keyword search
            }
        }

        // Fallback: basic keyword search
        $products = Product::with(['brand', 'category', 'variants', 'images', 'specifications', 'advantages'])
            ->where('is_active', true)
            ->where(function ($q) use ($query) {
                $q->where('name', 'LIKE', "%{$query}%")
                  ->orWhereHas('brand', fn($b) => $b->where('name', 'LIKE', "%{$query}%"))
                  ->orWhereHas('category', fn($c) => $c->where('name', 'LIKE', "%{$query}%"));
            })
            ->orderByRaw("CASE WHEN stock_status = 'in_stock' THEN 0 ELSE 1 END")
            ->orderBy('base_price')
            ->limit(8)
            ->get();

        $this->logSearch(request(), $query, 'keyword', null, $products);

        return Inertia::render('Customer/Comparison', [
            'products' => $products,
            'query' => $query,
            'recommendation' => '',
            'searchType' => 'keyword',
        ]);
    }

    private function logSearch(Request $request, string $query, string $type, ?float $budget, $products): void
    {
        try {
            $ua = $request->userAgent() ?? '';
            $device = 'desktop';
            if (preg_match('/Mobile|Android|iPhone/i', $ua)) $device = 'mobile';
            elseif (preg_match('/Tablet|iPad/i', $ua)) $device = 'tablet';

            SearchLog::create([
                'query' => mb_substr($query, 0, 255),
                'search_type' => $type,
                'budget' => $budget,
                'results_count' => $products->count(),
                'ip_address' => $request->ip(),
                'user_agent' => mb_substr($ua, 0, 500),
                'device_type' => $device,
                'matched_product_ids' => $products->pluck('id')->toArray(),
            ]);
        } catch (\Throwable $e) {
            // Fail silently — don't break the search
        }
    }

    private function getAIRecommendation($products, string $context): string
    {
        if ($products->isEmpty()) return '';

        $apiKey = config('anthropic.api_key') ?: env('ANTHROPIC_API_KEY');
        if (empty($apiKey)) return '';

        $list = $products->map(fn($p) => "{$p->name} - KSh " . number_format($p->base_price) . " ({$p->condition})")->implode("\n");

        try {
            $response = Http::withHeaders([
                'x-api-key' => $apiKey,
                'anthropic-version' => '2023-06-01',
                'content-type' => 'application/json',
            ])->timeout(15)->post('https://api.anthropic.com/v1/messages', [
                'model' => 'claude-sonnet-4-20250514',
                'max_tokens' => 256,
                'messages' => [[
                    'role' => 'user',
                    'content' => "You are a friendly phone/computer advisor for TechMart KE in Nairobi. A customer searched with {$context}. These products matched:\n{$list}\n\nGive a 2-3 sentence friendly recommendation. Compare the options briefly. Speak directly to the customer. Keep it casual and helpful for a Kenyan buyer. Plain text only, no markdown.",
                ]],
            ]);

            if ($response->successful()) {
                return $response->json('content.0.text', '');
            }
        } catch (\Exception $e) {
            // Fail silently
        }

        return '';
    }
}
