<?php

namespace Database\Seeders;

use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use App\Models\ProductImage;
use App\Models\ProductVariant;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class PhoneProductSeeder extends Seeder
{
    public function run(): void
    {
        // Ensure brands exist
        $apple = Brand::firstOrCreate(['slug' => 'apple'], ['name' => 'Apple', 'is_active' => true, 'sort_order' => 1]);
        $samsung = Brand::firstOrCreate(['slug' => 'samsung'], ['name' => 'Samsung', 'is_active' => true, 'sort_order' => 2]);

        // Ensure categories exist
        $phones = Category::firstOrCreate(['slug' => 'phones'], ['name' => 'Phones', 'is_active' => true, 'sort_order' => 1]);
        $iphones = Category::firstOrCreate(['slug' => 'iphones'], ['name' => 'iPhones', 'parent_id' => $phones->id, 'is_active' => true, 'sort_order' => 1]);
        $samsungPhones = Category::firstOrCreate(['slug' => 'samsung-phones'], ['name' => 'Samsung Phones', 'parent_id' => $phones->id, 'is_active' => true, 'sort_order' => 2]);

        // ========== iPHONE SERIES ==========
        $iphoneModels = [
            // iPhone 11 Series
            ['name' => 'iPhone 11', 'condition' => 'ex-uk', 'image' => 'https://placehold.co/400x400/f1f5f9/475569?font=roboto&text=apple-iphone-11.jpg', 'variants' => [
                ['storage' => '64GB', 'price' => 25000],
                ['storage' => '128GB', 'price' => 28000],
            ]],
            ['name' => 'iPhone 11 Pro', 'condition' => 'ex-uk', 'image' => 'https://placehold.co/400x400/f1f5f9/475569?font=roboto&text=apple-iphone-11-pro.jpg', 'variants' => [
                ['storage' => '64GB', 'price' => 30000],
                ['storage' => '256GB', 'price' => 35000],
            ]],
            ['name' => 'iPhone 11 Pro Max', 'condition' => 'ex-uk', 'image' => 'https://placehold.co/400x400/f1f5f9/475569?font=roboto&text=apple-iphone-11-pro-max.jpg', 'variants' => [
                ['storage' => '64GB', 'price' => 33000],
                ['storage' => '256GB', 'price' => 38000],
            ]],

            // iPhone 12 Series
            ['name' => 'iPhone 12 Mini', 'condition' => 'ex-uk', 'image' => 'https://placehold.co/400x400/f1f5f9/475569?font=roboto&text=apple-iphone-12-mini-.jpg', 'variants' => [
                ['storage' => '64GB', 'price' => 27000],
                ['storage' => '128GB', 'price' => 30000],
            ]],
            ['name' => 'iPhone 12', 'condition' => 'ex-uk', 'image' => 'https://placehold.co/400x400/f1f5f9/475569?font=roboto&text=apple-iphone-12-r1.jpg', 'variants' => [
                ['storage' => '64GB', 'price' => 32000],
                ['storage' => '128GB', 'price' => 35000],
            ]],
            ['name' => 'iPhone 12 Pro', 'condition' => 'ex-uk', 'image' => 'https://placehold.co/400x400/f1f5f9/475569?font=roboto&text=apple-iphone-12-pro-r1.jpg', 'variants' => [
                ['storage' => '128GB', 'price' => 40000],
                ['storage' => '256GB', 'price' => 45000],
            ]],
            ['name' => 'iPhone 12 Pro Max', 'condition' => 'ex-uk', 'image' => 'https://placehold.co/400x400/f1f5f9/475569?font=roboto&text=apple-iphone-12-pro-max-.jpg', 'variants' => [
                ['storage' => '128GB', 'price' => 45000],
                ['storage' => '256GB', 'price' => 50000],
            ]],

            // iPhone 13 Series
            ['name' => 'iPhone 13 Mini', 'condition' => 'ex-uk', 'image' => 'https://placehold.co/400x400/f1f5f9/475569?font=roboto&text=apple-iphone-13-mini.jpg', 'variants' => [
                ['storage' => '128GB', 'price' => 35000],
                ['storage' => '256GB', 'price' => 40000],
            ]],
            ['name' => 'iPhone 13', 'condition' => 'new', 'image' => 'https://placehold.co/400x400/f1f5f9/475569?font=roboto&text=apple-iphone-13.jpg', 'variants' => [
                ['storage' => '128GB', 'price' => 46000],
                ['storage' => '256GB', 'price' => 52000],
            ]],
            ['name' => 'iPhone 13 Pro', 'condition' => 'ex-uk', 'image' => 'https://placehold.co/400x400/f1f5f9/475569?font=roboto&text=apple-iphone-13-pro.jpg', 'variants' => [
                ['storage' => '128GB', 'price' => 50000],
                ['storage' => '256GB', 'price' => 57000],
            ]],
            ['name' => 'iPhone 13 Pro Max', 'condition' => 'ex-uk', 'image' => 'https://placehold.co/400x400/f1f5f9/475569?font=roboto&text=apple-iphone-13-pro-max.jpg', 'variants' => [
                ['storage' => '128GB', 'price' => 55000],
                ['storage' => '256GB', 'price' => 62000],
            ]],

            // iPhone 14 Series
            ['name' => 'iPhone 14', 'condition' => 'new', 'image' => 'https://placehold.co/400x400/f1f5f9/475569?font=roboto&text=apple-iphone-14.jpg', 'variants' => [
                ['storage' => '128GB', 'price' => 62000],
                ['storage' => '256GB', 'price' => 70000],
            ]],
            ['name' => 'iPhone 14 Plus', 'condition' => 'new', 'image' => 'https://placehold.co/400x400/f1f5f9/475569?font=roboto&text=apple-iphone-14-plus.jpg', 'variants' => [
                ['storage' => '128GB', 'price' => 68000],
                ['storage' => '256GB', 'price' => 76000],
            ]],
            ['name' => 'iPhone 14 Pro', 'condition' => 'new', 'image' => 'https://placehold.co/400x400/f1f5f9/475569?font=roboto&text=apple-iphone-14-pro.jpg', 'variants' => [
                ['storage' => '128GB', 'price' => 75000],
                ['storage' => '256GB', 'price' => 81000],
                ['storage' => '512GB', 'price' => 95000],
            ]],
            ['name' => 'iPhone 14 Pro Max', 'condition' => 'new', 'image' => 'https://placehold.co/400x400/f1f5f9/475569?font=roboto&text=apple-iphone-14-pro-max.jpg', 'variants' => [
                ['storage' => '128GB', 'price' => 85000],
                ['storage' => '256GB', 'price' => 92000],
                ['storage' => '512GB', 'price' => 105000],
            ]],

            // iPhone 15 Series
            ['name' => 'iPhone 15', 'condition' => 'new', 'image' => 'https://placehold.co/400x400/f1f5f9/475569?font=roboto&text=apple-iphone-15.jpg', 'variants' => [
                ['storage' => '128GB', 'price' => 85000],
                ['storage' => '256GB', 'price' => 95000],
            ]],
            ['name' => 'iPhone 15 Plus', 'condition' => 'new', 'image' => 'https://placehold.co/400x400/f1f5f9/475569?font=roboto&text=apple-iphone-15-plus.jpg', 'variants' => [
                ['storage' => '128GB', 'price' => 95000],
                ['storage' => '256GB', 'price' => 105000],
            ]],
            ['name' => 'iPhone 15 Pro', 'condition' => 'new', 'image' => 'https://placehold.co/400x400/f1f5f9/475569?font=roboto&text=apple-iphone-15-pro.jpg', 'variants' => [
                ['storage' => '128GB', 'price' => 115000],
                ['storage' => '256GB', 'price' => 125000],
                ['storage' => '512GB', 'price' => 145000],
                ['storage' => '1TB', 'price' => 175000],
            ]],
            ['name' => 'iPhone 15 Pro Max', 'condition' => 'new', 'image' => 'https://placehold.co/400x400/f1f5f9/475569?font=roboto&text=apple-iphone-15-pro-max.jpg', 'variants' => [
                ['storage' => '256GB', 'price' => 145000],
                ['storage' => '512GB', 'price' => 165000],
                ['storage' => '1TB', 'price' => 195000],
            ]],

            // iPhone 16 Series
            ['name' => 'iPhone 16', 'condition' => 'new', 'image' => 'https://placehold.co/400x400/f1f5f9/475569?font=roboto&text=apple-iphone-16.jpg', 'variants' => [
                ['storage' => '128GB', 'price' => 110000],
                ['storage' => '256GB', 'price' => 120000],
            ]],
            ['name' => 'iPhone 16 Plus', 'condition' => 'new', 'image' => 'https://placehold.co/400x400/f1f5f9/475569?font=roboto&text=apple-iphone-16-plus.jpg', 'variants' => [
                ['storage' => '128GB', 'price' => 125000],
                ['storage' => '256GB', 'price' => 135000],
            ]],
            ['name' => 'iPhone 16 Pro', 'condition' => 'new', 'image' => 'https://placehold.co/400x400/f1f5f9/475569?font=roboto&text=apple-iphone-16-pro.jpg', 'variants' => [
                ['storage' => '128GB', 'price' => 145000],
                ['storage' => '256GB', 'price' => 155000],
                ['storage' => '512GB', 'price' => 175000],
                ['storage' => '1TB', 'price' => 210000],
            ]],
            ['name' => 'iPhone 16 Pro Max', 'condition' => 'new', 'image' => 'https://placehold.co/400x400/f1f5f9/475569?font=roboto&text=apple-iphone-16-pro-max.jpg', 'variants' => [
                ['storage' => '256GB', 'price' => 175000],
                ['storage' => '512GB', 'price' => 195000],
                ['storage' => '1TB', 'price' => 230000],
            ]],
        ];

        // ========== SAMSUNG SERIES ==========
        $samsungModels = [
            // Galaxy S Series (Ex-UK)
            ['name' => 'Samsung S10e', 'condition' => 'ex-uk', 'image' => 'https://placehold.co/400x400/f1f5f9/475569?font=roboto&text=samsung-galaxy-s10e-.jpg', 'variants' => [
                ['storage' => '128GB', 'price' => 15000],
            ]],
            ['name' => 'Samsung S10', 'condition' => 'ex-uk', 'image' => 'https://placehold.co/400x400/f1f5f9/475569?font=roboto&text=samsung-galaxy-s10-r1.jpg', 'variants' => [
                ['storage' => '128GB', 'price' => 19000],
            ]],
            ['name' => 'Samsung S10 Plus', 'condition' => 'ex-uk', 'image' => 'https://placehold.co/400x400/f1f5f9/475569?font=roboto&text=samsung-galaxy-s10-plus-.jpg', 'variants' => [
                ['storage' => '128GB', 'price' => 22000],
            ]],
            ['name' => 'Samsung Note 10', 'condition' => 'ex-uk', 'image' => 'https://placehold.co/400x400/f1f5f9/475569?font=roboto&text=samsung-galaxy-note10-r1.jpg', 'variants' => [
                ['storage' => '256GB', 'price' => 22000],
            ]],
            ['name' => 'Samsung S20 FE', 'condition' => 'ex-uk', 'image' => 'https://placehold.co/400x400/f1f5f9/475569?font=roboto&text=samsung-galaxy-s20-fe.jpg', 'variants' => [
                ['storage' => '128GB', 'price' => 18000],
            ]],
            ['name' => 'Samsung S20', 'condition' => 'ex-uk', 'image' => 'https://placehold.co/400x400/f1f5f9/475569?font=roboto&text=samsung-galaxy-s20-5g-r1.jpg', 'variants' => [
                ['storage' => '128GB', 'price' => 22000],
            ]],
            ['name' => 'Samsung Note 20 Ultra', 'condition' => 'ex-uk', 'image' => 'https://placehold.co/400x400/f1f5f9/475569?font=roboto&text=samsung-galaxy-note20-ultra-5g-r1.jpg', 'variants' => [
                ['storage' => '128GB', 'price' => 37000],
            ]],
            ['name' => 'Samsung S21', 'condition' => 'ex-uk', 'image' => 'https://placehold.co/400x400/f1f5f9/475569?font=roboto&text=samsung-galaxy-s21-5g-r1.jpg', 'variants' => [
                ['storage' => '128GB', 'price' => 23000],
                ['storage' => '256GB', 'price' => 26000],
            ]],
            ['name' => 'Samsung S21 Plus', 'condition' => 'ex-uk', 'image' => 'https://placehold.co/400x400/f1f5f9/475569?font=roboto&text=samsung-galaxy-s21-plus-5g.jpg', 'variants' => [
                ['storage' => '128GB', 'price' => 25000],
                ['storage' => '256GB', 'price' => 28000],
            ]],
            ['name' => 'Samsung S21 Ultra', 'condition' => 'ex-uk', 'image' => 'https://placehold.co/400x400/f1f5f9/475569?font=roboto&text=samsung-galaxy-s21-ultra-5g-r1.jpg', 'variants' => [
                ['storage' => '128GB', 'price' => 35000],
                ['storage' => '256GB', 'price' => 40000],
            ]],
            ['name' => 'Samsung S22', 'condition' => 'ex-uk', 'image' => 'https://placehold.co/400x400/f1f5f9/475569?font=roboto&text=samsung-galaxy-s22-5g.jpg', 'variants' => [
                ['storage' => '128GB', 'price' => 29000],
                ['storage' => '256GB', 'price' => 33000],
            ]],
            ['name' => 'Samsung S22 Plus', 'condition' => 'ex-uk', 'image' => 'https://placehold.co/400x400/f1f5f9/475569?font=roboto&text=samsung-galaxy-s22-plus-5g.jpg', 'variants' => [
                ['storage' => '128GB', 'price' => 33000],
                ['storage' => '256GB', 'price' => 37000],
            ]],
            ['name' => 'Samsung S22 Ultra', 'condition' => 'ex-uk', 'image' => 'https://placehold.co/400x400/f1f5f9/475569?font=roboto&text=samsung-galaxy-s22-ultra-5g.jpg', 'variants' => [
                ['storage' => '128GB', 'price' => 42000],
                ['storage' => '256GB', 'price' => 48000],
            ]],
            ['name' => 'Samsung S23', 'condition' => 'new', 'image' => 'https://placehold.co/400x400/f1f5f9/475569?font=roboto&text=samsung-galaxy-s23.jpg', 'variants' => [
                ['storage' => '128GB', 'price' => 55000],
                ['storage' => '256GB', 'price' => 62000],
            ]],
            ['name' => 'Samsung S23 Plus', 'condition' => 'new', 'image' => 'https://placehold.co/400x400/f1f5f9/475569?font=roboto&text=samsung-galaxy-s23-plus.jpg', 'variants' => [
                ['storage' => '256GB', 'price' => 75000],
            ]],
            ['name' => 'Samsung S23 Ultra', 'condition' => 'new', 'image' => 'https://placehold.co/400x400/f1f5f9/475569?font=roboto&text=samsung-galaxy-s23-ultra-5g.jpg', 'variants' => [
                ['storage' => '256GB', 'price' => 120000],
                ['storage' => '512GB', 'price' => 135000],
            ]],
            ['name' => 'Samsung S24', 'condition' => 'new', 'image' => 'https://placehold.co/400x400/f1f5f9/475569?font=roboto&text=samsung-galaxy-s24.jpg', 'variants' => [
                ['storage' => '128GB', 'price' => 72000],
                ['storage' => '256GB', 'price' => 80000],
            ]],
            ['name' => 'Samsung S24 Plus', 'condition' => 'new', 'image' => 'https://placehold.co/400x400/f1f5f9/475569?font=roboto&text=samsung-galaxy-s24-plus.jpg', 'variants' => [
                ['storage' => '256GB', 'price' => 95000],
            ]],
            ['name' => 'Samsung S24 Ultra', 'condition' => 'new', 'image' => 'https://placehold.co/400x400/f1f5f9/475569?font=roboto&text=samsung-galaxy-s24-ultra-5g.jpg', 'variants' => [
                ['storage' => '256GB', 'price' => 130000],
                ['storage' => '512GB', 'price' => 145000],
                ['storage' => '1TB', 'price' => 170000],
            ]],
            ['name' => 'Samsung S25', 'condition' => 'new', 'image' => 'https://placehold.co/400x400/f1f5f9/475569?font=roboto&text=samsung-galaxy-s25.jpg', 'variants' => [
                ['storage' => '128GB', 'price' => 85000],
                ['storage' => '256GB', 'price' => 95000],
            ]],
            ['name' => 'Samsung S25 Plus', 'condition' => 'new', 'image' => 'https://placehold.co/400x400/f1f5f9/475569?font=roboto&text=samsung-galaxy-s25-plus.jpg', 'variants' => [
                ['storage' => '256GB', 'price' => 110000],
            ]],
            ['name' => 'Samsung S25 Ultra', 'condition' => 'new', 'image' => 'https://placehold.co/400x400/f1f5f9/475569?font=roboto&text=samsung-galaxy-s25-ultra.jpg', 'variants' => [
                ['storage' => '256GB', 'price' => 150000],
                ['storage' => '512GB', 'price' => 170000],
                ['storage' => '1TB', 'price' => 200000],
            ]],

            // Galaxy A Series
            ['name' => 'Samsung A14', 'condition' => 'new', 'image' => 'https://placehold.co/400x400/f1f5f9/475569?font=roboto&text=samsung-galaxy-a14.jpg', 'variants' => [
                ['storage' => '64GB', 'price' => 15000],
                ['storage' => '128GB', 'price' => 18000],
            ]],
            ['name' => 'Samsung A15', 'condition' => 'new', 'image' => 'https://placehold.co/400x400/f1f5f9/475569?font=roboto&text=samsung-galaxy-a15.jpg', 'variants' => [
                ['storage' => '128GB', 'price' => 19000],
            ]],
            ['name' => 'Samsung A25', 'condition' => 'new', 'image' => 'https://placehold.co/400x400/f1f5f9/475569?font=roboto&text=samsung-galaxy-a25.jpg', 'variants' => [
                ['storage' => '128GB', 'price' => 25000],
            ]],
            ['name' => 'Samsung A35', 'condition' => 'new', 'image' => 'https://placehold.co/400x400/f1f5f9/475569?font=roboto&text=samsung-galaxy-a35.jpg', 'variants' => [
                ['storage' => '128GB', 'price' => 32000],
                ['storage' => '256GB', 'price' => 37000],
            ]],
            ['name' => 'Samsung A55', 'condition' => 'new', 'image' => 'https://placehold.co/400x400/f1f5f9/475569?font=roboto&text=samsung-galaxy-a55.jpg', 'variants' => [
                ['storage' => '128GB', 'price' => 40000],
                ['storage' => '256GB', 'price' => 45000],
            ]],

            // Galaxy Z Series (Foldables)
            ['name' => 'Samsung Z Flip 5', 'condition' => 'new', 'image' => 'https://placehold.co/400x400/f1f5f9/475569?font=roboto&text=samsung-galaxy-z-flip5.jpg', 'variants' => [
                ['storage' => '256GB', 'price' => 85000],
                ['storage' => '512GB', 'price' => 100000],
            ]],
            ['name' => 'Samsung Z Fold 5', 'condition' => 'new', 'image' => 'https://placehold.co/400x400/f1f5f9/475569?font=roboto&text=samsung-galaxy-z-fold5.jpg', 'variants' => [
                ['storage' => '256GB', 'price' => 135000],
                ['storage' => '512GB', 'price' => 155000],
            ]],
            ['name' => 'Samsung Z Flip 6', 'condition' => 'new', 'image' => 'https://placehold.co/400x400/f1f5f9/475569?font=roboto&text=samsung-galaxy-z-flip6.jpg', 'variants' => [
                ['storage' => '256GB', 'price' => 95000],
                ['storage' => '512GB', 'price' => 110000],
            ]],
            ['name' => 'Samsung Z Fold 6', 'condition' => 'new', 'image' => 'https://placehold.co/400x400/f1f5f9/475569?font=roboto&text=samsung-galaxy-z-fold6.jpg', 'variants' => [
                ['storage' => '256GB', 'price' => 150000],
                ['storage' => '512GB', 'price' => 175000],
            ]],
        ];

        $this->seedProducts($iphoneModels, $apple->id, $iphones->id);
        $this->seedProducts($samsungModels, $samsung->id, $samsungPhones->id);
    }

    private function seedProducts(array $models, int $brandId, int $categoryId): void
    {
        foreach ($models as $model) {
            $slug = Str::slug($model['name']);
            $minPrice = collect($model['variants'])->min('price');

            $product = Product::firstOrCreate(
                ['slug' => $slug],
                [
                    'name' => $model['name'],
                    'base_sku' => strtoupper(Str::slug($model['name'], '-')),
                    'brand_id' => $brandId,
                    'category_id' => $categoryId,
                    'base_price' => $minPrice,
                    'original_price' => $minPrice,
                    'condition' => $model['condition'],
                    'is_active' => true,
                    'stock_status' => 'out_of_stock',
                    'short_description' => $model['name'] . ' available at TechMart Kenya',
                ]
            );

            foreach ($model['variants'] as $variant) {
                $sku = strtoupper(Str::slug($model['name'], '-')) . '-' . strtoupper(Str::slug($variant['storage'], '-'));

                ProductVariant::firstOrCreate(
                    ['product_id' => $product->id, 'storage' => $variant['storage']],
                    [
                        'sku' => $sku,
                        'price' => $variant['price'],
                        'cost_price' => round($variant['price'] * 0.85),
                        'stock_quantity' => 0,
                        'is_active' => true,
                    ]
                );
            }

            // Add placeholder image if product has no images
            if ($product->images()->count() === 0) {
                $encodedName = urlencode($model['name']);
                ProductImage::create([
                    'product_id' => $product->id,
                    'image_url' => "https://placehold.co/400x400/f1f5f9/475569?font=roboto&text={$encodedName}",
                    'alt_text' => $model['name'],
                    'is_primary' => true,
                    'image_type' => 'main',
                    'sort_order' => 0,
                ]);
            }
        }
    }
}
