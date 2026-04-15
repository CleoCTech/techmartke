<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use App\Models\ProductImage;
use App\Models\ProductVariant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use App\Services\ProductImageService;
use Illuminate\Support\Str;
use Inertia\Inertia;

class ProductController extends Controller
{
    public function __construct()
    {
        $this->middleware('gen-auth');
        $this->middleware('admin-auth');
    }

    public function index(Request $request)
    {
        $query = Product::with(['brand', 'category', 'variants', 'images']);

        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'LIKE', "%{$search}%")
                  ->orWhere('base_sku', 'LIKE', "%{$search}%");
            });
        }

        if ($request->has('category_id') && $request->category_id) {
            $query->where('category_id', $request->category_id);
        }

        if ($request->has('brand_id') && $request->brand_id) {
            $query->where('brand_id', $request->brand_id);
        }

        $products = $query->orderBy('created_at', 'desc')->paginate(15);

        return Inertia::render('Admin/Products/Index', [
            'products' => $products,
            'filters' => $request->only(['search', 'category_id', 'brand_id']),
            'brands' => Brand::where('is_active', true)->get(),
            'categories' => Category::where('is_active', true)->get(),
        ]);
    }

    public function create()
    {
        return Inertia::render('Admin/Products/Create', [
            'brands' => Brand::where('is_active', true)->get(),
            'categories' => Category::where('is_active', true)->get(),
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'brand_id' => 'required|exists:brands,id',
            'category_id' => 'required|exists:categories,id',
            'description' => 'nullable|string',
            'short_description' => 'nullable|string|max:500',
            'condition' => 'nullable|string|max:50',
            'base_price' => 'required|numeric|min:0',
            'original_price' => 'nullable|numeric|min:0',
            'cost_price' => 'nullable|numeric|min:0',
            'base_sku' => 'nullable|string|max:100',
            'is_active' => 'boolean',
            'featured' => 'boolean',
            'is_flash_sale' => 'boolean',
            'flash_sale_price' => 'nullable|numeric|min:0',
            'flash_sale_ends_at' => 'nullable|date',
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string|max:500',
            'variants' => 'nullable|array',
            'variants.*.storage' => 'nullable|string|max:50',
            'variants.*.sim_type' => 'nullable|string|max:50',
            'variants.*.color' => 'nullable|string|max:50',
            'variants.*.price' => 'required_with:variants|numeric|min:0',
            'variants.*.cost_price' => 'nullable|numeric|min:0',
            'variants.*.stock_quantity' => 'nullable|integer|min:0',
            'variants.*.sku' => 'nullable|string|max:100',
            'images' => 'nullable|array',
            'images.*' => 'nullable|file|mimetypes:image/jpeg,image/png,image/gif,image/webp,image/avif,image/heic,image/heif|max:5120',
            'specifications' => 'nullable|array',
            'specifications.*.spec_group' => 'nullable|string|max:100',
            'specifications.*.spec_name' => 'nullable|string|max:100',
            'specifications.*.spec_value' => 'nullable|string|max:255',
            'advantages' => 'nullable|array',
            'advantages.*' => 'nullable|string|max:500',
        ]);

        DB::beginTransaction();
        try {
            $product = Product::create([
                'name' => $validated['name'],
                'slug' => Str::slug($validated['name']),
                'brand_id' => $validated['brand_id'],
                'category_id' => $validated['category_id'],
                'description' => $validated['description'] ?? null,
                'short_description' => $validated['short_description'] ?? null,
                'condition' => $validated['condition'] ?? 'new',
                'base_price' => $validated['base_price'],
                'original_price' => $validated['original_price'] ?? null,
                'cost_price' => $validated['cost_price'] ?? null,
                'base_sku' => $validated['base_sku'] ?? null,
                'is_active' => $validated['is_active'] ?? true,
                'featured' => $validated['featured'] ?? false,
                'is_flash_sale' => $validated['is_flash_sale'] ?? false,
                'flash_sale_price' => $validated['flash_sale_price'] ?? null,
                'flash_sale_ends_at' => $validated['flash_sale_ends_at'] ?? null,
                'meta_title' => $validated['meta_title'] ?? null,
                'meta_description' => $validated['meta_description'] ?? null,
            ]);

            if (!empty($validated['variants'])) {
                foreach ($validated['variants'] as $variant) {
                    $product->variants()->create($variant);
                }
            }

            if ($request->hasFile('images')) {
                foreach ($request->file('images') as $imageFile) {
                    $path = $imageFile->store('products/images', 'public');
                    $product->images()->create([
                        'image_url' => '/storage/' . $path,
                        'alt_text' => $product->name,
                        'is_primary' => $product->images()->count() === 0,
                    ]);
                }
            }

            if (!empty($validated['specifications'])) {
                foreach ($validated['specifications'] as $spec) {
                    if (!empty($spec['spec_name'])) {
                        $product->specifications()->create($spec);
                    }
                }
            }

            if (!empty($validated['advantages'])) {
                foreach ($validated['advantages'] as $adv) {
                    if (!empty($adv)) {
                        $product->advantages()->create(['advantage' => $adv]);
                    }
                }
            }

            DB::commit();

            if ($product->images()->count() === 0) {
                ProductImageService::fetchAndAttachImage($product);
            }

            return redirect()->back()->with('success', 'Product created successfully.');
        } catch (\Throwable $th) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Failed to create product: ' . $th->getMessage());
        }
    }

    public function show($uuid)
    {
        $product = Product::with(['brand', 'category', 'variants', 'images', 'specifications', 'advantages'])
            ->where('uuid', $uuid)->firstOrFail();

        return Inertia::render('Admin/Products/Show', [
            'product' => $product,
        ]);
    }

    public function edit($uuid)
    {
        $product = Product::with(['variants', 'images', 'specifications', 'advantages'])
            ->where('uuid', $uuid)->firstOrFail();

        return Inertia::render('Admin/Products/Edit', [
            'product' => $product,
            'brands' => Brand::where('is_active', true)->get(),
            'categories' => Category::where('is_active', true)->get(),
        ]);
    }

    public function update(Request $request, $uuid)
    {
        $product = Product::where('uuid', $uuid)->firstOrFail();

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'brand_id' => 'required|exists:brands,id',
            'category_id' => 'required|exists:categories,id',
            'description' => 'nullable|string',
            'short_description' => 'nullable|string|max:500',
            'condition' => 'nullable|string|max:50',
            'base_price' => 'required|numeric|min:0',
            'original_price' => 'nullable|numeric|min:0',
            'cost_price' => 'nullable|numeric|min:0',
            'base_sku' => 'nullable|string|max:100',
            'is_active' => 'boolean',
            'featured' => 'boolean',
            'is_flash_sale' => 'boolean',
            'flash_sale_price' => 'nullable|numeric|min:0',
            'flash_sale_ends_at' => 'nullable|date',
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string|max:500',
            'specifications' => 'nullable|array',
            'specifications.*.spec_group' => 'nullable|string|max:100',
            'specifications.*.spec_name' => 'nullable|string|max:100',
            'specifications.*.spec_value' => 'nullable|string|max:255',
            'advantages' => 'nullable|array',
            'advantages.*' => 'nullable|string|max:500',
            'images' => 'nullable|array',
            'images.*' => 'nullable|file|mimetypes:image/jpeg,image/png,image/gif,image/webp,image/avif,image/heic,image/heif|max:5120',
        ]);

        $specs = $validated['specifications'] ?? null;
        $advs = $validated['advantages'] ?? null;
        $images = $request->file('images');
        unset($validated['specifications'], $validated['advantages'], $validated['images']);

        DB::beginTransaction();
        try {
            $product->update($validated);

            if ($specs !== null) {
                $product->specifications()->delete();
                foreach ($specs as $spec) {
                    if (!empty($spec['spec_name'])) {
                        $product->specifications()->create($spec);
                    }
                }
            }

            if ($advs !== null) {
                $product->advantages()->delete();
                foreach ($advs as $adv) {
                    if (!empty($adv)) {
                        $product->advantages()->create(['advantage' => $adv]);
                    }
                }
            }

            // Handle uploaded images
            if ($images) {
                foreach ($images as $imageFile) {
                    $path = $imageFile->store('products/images', 'public');
                    $product->images()->create([
                        'image_url' => '/storage/' . $path,
                        'alt_text' => $product->name,
                        'is_primary' => $product->images()->count() === 0,
                    ]);
                }
            }

            DB::commit();

            return redirect()->back()->with('success', 'Product updated successfully.');
        } catch (\Throwable $th) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Failed to update product: ' . $th->getMessage());
        }
    }

    public function generateContent(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string',
            'brand' => 'nullable|string',
            'category' => 'nullable|string',
            'condition' => 'nullable|string',
            'base_price' => 'nullable|numeric',
            'variants' => 'nullable|array',
        ]);

        $apiKey = config('anthropic.api_key') ?: env('ANTHROPIC_API_KEY');
        if (empty($apiKey)) {
            return response()->json(['error' => 'Anthropic API key is not configured.'], 422);
        }

        $brandName = $validated['brand'] ?? '';
        $categoryName = $validated['category'] ?? '';
        $condition = $validated['condition'] ?? 'new';
        $price = $validated['base_price'] ?? 0;
        $variants = collect($validated['variants'] ?? [])->map(fn($v) => ($v['storage'] ?? '') . ' ' . ($v['color'] ?? ''))->filter()->implode(', ');

        $prompt = <<<PROMPT
You are a product copywriter for TechMart KE, a phone and computer e-commerce store in Nairobi, Kenya. Prices are in KES.

Generate content for this product:
- Name: {$validated['name']}
- Brand: {$brandName}
- Category: {$categoryName}
- Condition: {$condition}
- Price: KSh {$price}
- Variants: {$variants}

Generate the following in JSON format (no markdown, no explanation):
{
  "short_description": "A compelling 1-2 sentence product summary (max 150 chars). Highlight key selling points.",
  "description": "A detailed 3-5 paragraph product description. Include key features, what makes it great, who it's for, and why buy from TechMart KE. Use simple language for Kenyan buyers. If Ex-UK/Ex-US, mention it's been quality tested.",
  "meta_title": "SEO-optimized page title (max 60 chars). Format: Product Name - Price | TechMart KE",
  "meta_description": "SEO meta description (max 155 chars). Include product name, price, condition, and a call to action.",
  "specifications": [
    {"spec_group": "Group name (e.g. Display, Performance, Camera, Battery, Connectivity, Storage)", "spec_name": "Spec name", "spec_value": "Spec value"}
  ],
  "advantages": ["advantage 1", "advantage 2", "advantage 3"]
}

For specifications: Generate 8-15 realistic specs based on known product data. Group them logically (Display, Performance, Camera, Battery, Storage, Connectivity, Design). Use real/accurate values if you know them for this specific product model. If unsure, use reasonable estimates.

For advantages: Generate 4-6 compelling selling points. Focus on what matters to Kenyan buyers (value for money, durability, battery life, camera quality, etc.).
PROMPT;

        try {
            $response = Http::withHeaders([
                'x-api-key' => $apiKey,
                'anthropic-version' => '2023-06-01',
                'content-type' => 'application/json',
            ])->timeout(30)->post('https://api.anthropic.com/v1/messages', [
                'model' => env('ANTHROPIC_MODEL', 'claude-sonnet-4-5'),
                'max_tokens' => 2048,
                'messages' => [['role' => 'user', 'content' => $prompt]],
            ]);

            if (!$response->successful()) {
                return response()->json(['error' => 'AI service error: ' . ($response->json('error.message') ?? 'Unknown')], 422);
            }

            $text = $response->json('content.0.text', '');

            // Extract JSON
            $decoded = json_decode($text, true);
            if (!$decoded) {
                if (preg_match('/\{[\s\S]*\}/', $text, $m)) {
                    $decoded = json_decode($m[0], true);
                }
            }

            if (!$decoded) {
                return response()->json(['error' => 'Failed to parse AI response.'], 422);
            }

            return response()->json($decoded);
        } catch (\Exception $e) {
            return response()->json(['error' => 'AI generation failed: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Delete a single product image by its ID.
     * Removes the file from storage if it lives in /storage/, then deletes the DB row.
     * If the deleted image was primary, promotes the next remaining image to primary.
     */
    public function destroyImage($id)
    {
        $image = ProductImage::findOrFail($id);
        $product = $image->product;
        $wasPrimary = $image->is_primary;

        DB::beginTransaction();
        try {
            // Best-effort: remove file from local storage if it's a /storage/... URL
            $url = $image->image_url;
            if ($url && str_starts_with($url, '/storage/')) {
                $relative = ltrim(substr($url, strlen('/storage/')), '/');
                if (Storage::disk('public')->exists($relative)) {
                    Storage::disk('public')->delete($relative);
                }
            }

            $image->delete();

            // If we deleted the primary, promote the next remaining image
            if ($wasPrimary && $product) {
                $next = $product->images()->orderBy('sort_order')->orderBy('id')->first();
                if ($next) {
                    $next->update(['is_primary' => true]);
                }
            }

            DB::commit();
            return redirect()->back()->with('success', 'Image deleted successfully.');
        } catch (\Throwable $th) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Failed to delete image: ' . $th->getMessage());
        }
    }

    public function destroy($uuid)
    {
        $product = Product::where('uuid', $uuid)->firstOrFail();

        DB::beginTransaction();
        try {
            $product->variants()->delete();
            $product->images()->delete();
            $product->specifications()->delete();
            $product->delete();
            DB::commit();

            return redirect()->back()->with('success', 'Product deleted successfully.');
        } catch (\Throwable $th) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Failed to delete product: ' . $th->getMessage());
        }
    }
}
