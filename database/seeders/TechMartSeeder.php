<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\ProductImage;
use App\Models\ProductSpecification;
use App\Models\ProductAdvantage;
use App\Models\AIKnowledgeBase;

class TechMartSeeder extends Seeder
{
    public function run(): void
    {
        // Create Brands
        $brands = [
            ['name' => 'Apple', 'slug' => 'apple', 'is_active' => true, 'sort_order' => 1],
            ['name' => 'Samsung', 'slug' => 'samsung', 'is_active' => true, 'sort_order' => 2],
            ['name' => 'Google', 'slug' => 'google', 'is_active' => true, 'sort_order' => 3],
            ['name' => 'Dell', 'slug' => 'dell', 'is_active' => true, 'sort_order' => 4],
            ['name' => 'HP', 'slug' => 'hp', 'is_active' => true, 'sort_order' => 5],
            ['name' => 'Lenovo', 'slug' => 'lenovo', 'is_active' => true, 'sort_order' => 6],
        ];
        foreach ($brands as $brand) {
            Brand::firstOrCreate(['slug' => $brand['slug']], $brand);
        }

        // Create Categories (with hierarchy)
        $phones = Category::firstOrCreate(['slug' => 'phones'], ['name' => 'Phones', 'slug' => 'phones', 'description' => 'Mobile phones and smartphones', 'is_active' => true, 'sort_order' => 1]);
        $computers = Category::firstOrCreate(['slug' => 'computers'], ['name' => 'Computers', 'slug' => 'computers', 'description' => 'Laptops, desktops and workstations', 'is_active' => true, 'sort_order' => 2]);
        // Sub-categories
        Category::firstOrCreate(['slug' => 'iphones'], ['name' => 'iPhones', 'slug' => 'iphones', 'parent_id' => $phones->id, 'is_active' => true, 'sort_order' => 1]);
        Category::firstOrCreate(['slug' => 'samsung-phones'], ['name' => 'Samsung Phones', 'slug' => 'samsung-phones', 'parent_id' => $phones->id, 'is_active' => true, 'sort_order' => 2]);
        Category::firstOrCreate(['slug' => 'macbooks'], ['name' => 'MacBooks', 'slug' => 'macbooks', 'parent_id' => $computers->id, 'is_active' => true, 'sort_order' => 1]);
        Category::firstOrCreate(['slug' => 'windows-laptops'], ['name' => 'Windows Laptops', 'slug' => 'windows-laptops', 'parent_id' => $computers->id, 'is_active' => true, 'sort_order' => 2]);

        // Skip sample products in production — only seed brands/categories
        if (env('SEED_SAMPLE_PRODUCTS', false) === false) {
            return;
        }

        $apple = Brand::where('slug', 'apple')->first();
        $samsung = Brand::where('slug', 'samsung')->first();

        // Products - iPhones
        $products = [
            [
                'brand_id' => $apple->id,
                'category_id' => $phones->id,
                'name' => 'iPhone 14 Pro',
                'slug' => 'iphone-14-pro',
                'base_sku' => 'IPH-14-PRO',
                'description' => 'The iPhone 14 Pro features an Always-On display, 48MP camera system, and the A16 Bionic chip.',
                'short_description' => 'Pro. Beyond.',
                'condition' => 'ex-uk',
                'base_price' => 81000,
                'original_price' => 95000,
                'is_active' => true,
                'featured' => true,
                'stock_status' => 'in_stock',
                'variants' => [
                    ['sku' => 'IPH-14-PRO-128-ESIM', 'storage' => '128GB', 'sim_type' => 'eSIM', 'color' => 'Space Black', 'price' => 75000, 'stock_quantity' => 8],
                    ['sku' => 'IPH-14-PRO-256-ESIM', 'storage' => '256GB', 'sim_type' => 'eSIM', 'color' => 'Space Black', 'price' => 81000, 'stock_quantity' => 5],
                    ['sku' => 'IPH-14-PRO-512-ESIM', 'storage' => '512GB', 'sim_type' => 'eSIM', 'color' => 'Deep Purple', 'price' => 95000, 'stock_quantity' => 3],
                ],
                'specs' => [
                    ['spec_name' => 'Display', 'spec_value' => '6.1" Super Retina XDR', 'spec_group' => 'Display'],
                    ['spec_name' => 'Resolution', 'spec_value' => '2556 x 1179', 'spec_group' => 'Display'],
                    ['spec_name' => 'Chip', 'spec_value' => 'A16 Bionic', 'spec_group' => 'Performance'],
                    ['spec_name' => 'RAM', 'spec_value' => '6GB', 'spec_group' => 'Performance'],
                    ['spec_name' => 'Camera', 'spec_value' => '48MP Main + 12MP Ultra Wide + 12MP Telephoto', 'spec_group' => 'Camera'],
                    ['spec_name' => 'Battery', 'spec_value' => '3200mAh - All-day battery life', 'spec_group' => 'Battery'],
                ],
                'advantages' => ['A16 Bionic Chip', 'Dynamic Island', '48MP Camera System', 'Always-On Display', 'iOS Ecosystem'],
            ],
            [
                'brand_id' => $apple->id,
                'category_id' => $phones->id,
                'name' => 'iPhone 13',
                'slug' => 'iphone-13',
                'base_sku' => 'IPH-13',
                'description' => 'iPhone 13 with A15 Bionic chip, advanced dual-camera system, and all-day battery life.',
                'short_description' => 'Your new superpower.',
                'condition' => 'ex-uk',
                'base_price' => 46000,
                'original_price' => 55000,
                'is_active' => true,
                'featured' => true,
                'stock_status' => 'in_stock',
                'variants' => [
                    ['sku' => 'IPH-13-128-PHY', 'storage' => '128GB', 'sim_type' => 'Physical', 'color' => 'Midnight', 'price' => 46000, 'stock_quantity' => 12],
                    ['sku' => 'IPH-13-256-PHY', 'storage' => '256GB', 'sim_type' => 'Physical', 'color' => 'Starlight', 'price' => 52000, 'stock_quantity' => 7],
                ],
                'specs' => [
                    ['spec_name' => 'Display', 'spec_value' => '6.1" Super Retina XDR', 'spec_group' => 'Display'],
                    ['spec_name' => 'Chip', 'spec_value' => 'A15 Bionic', 'spec_group' => 'Performance'],
                    ['spec_name' => 'Camera', 'spec_value' => '12MP Dual Camera', 'spec_group' => 'Camera'],
                    ['spec_name' => 'Battery', 'spec_value' => 'All-day battery life', 'spec_group' => 'Battery'],
                ],
                'advantages' => ['A15 Bionic Chip', 'Excellent Camera System', 'iOS Ecosystem', 'Great Battery Life'],
            ],
            [
                'brand_id' => $samsung->id,
                'category_id' => $phones->id,
                'name' => 'Samsung S23 Ultra',
                'slug' => 'samsung-s23-ultra',
                'base_sku' => 'SAM-S23-ULTRA',
                'description' => 'Samsung Galaxy S23 Ultra with 200MP camera, S Pen, and Snapdragon 8 Gen 2.',
                'short_description' => 'Epic, just like that.',
                'condition' => 'new',
                'base_price' => 120000,
                'original_price' => 140000,
                'is_active' => true,
                'featured' => true,
                'stock_status' => 'in_stock',
                'variants' => [
                    ['sku' => 'SAM-S23-U-256-DUAL', 'storage' => '256GB', 'sim_type' => 'Dual SIM', 'color' => 'Phantom Black', 'price' => 120000, 'stock_quantity' => 6],
                    ['sku' => 'SAM-S23-U-512-DUAL', 'storage' => '512GB', 'sim_type' => 'Dual SIM', 'color' => 'Green', 'price' => 135000, 'stock_quantity' => 4],
                ],
                'specs' => [
                    ['spec_name' => 'Display', 'spec_value' => '6.8" Dynamic AMOLED 2X 120Hz', 'spec_group' => 'Display'],
                    ['spec_name' => 'Chip', 'spec_value' => 'Snapdragon 8 Gen 2', 'spec_group' => 'Performance'],
                    ['spec_name' => 'RAM', 'spec_value' => '12GB', 'spec_group' => 'Performance'],
                    ['spec_name' => 'Camera', 'spec_value' => '200MP Main + 12MP Ultra Wide + 10MP Telephoto', 'spec_group' => 'Camera'],
                    ['spec_name' => 'Battery', 'spec_value' => '5000mAh', 'spec_group' => 'Battery'],
                ],
                'advantages' => ['200MP Camera', '120Hz AMOLED Display', 'S Pen Included', '5000mAh Battery', 'Expandable Storage'],
            ],
            [
                'brand_id' => $samsung->id,
                'category_id' => $phones->id,
                'name' => 'Samsung S21',
                'slug' => 'samsung-s21',
                'base_sku' => 'SAM-S21',
                'description' => 'Samsung Galaxy S21 with 64MP triple camera, 120Hz display, and Exynos 2100.',
                'short_description' => 'Everyday epic.',
                'condition' => 'ex-uk',
                'base_price' => 47500,
                'original_price' => 60000,
                'is_active' => true,
                'featured' => false,
                'stock_status' => 'in_stock',
                'variants' => [
                    ['sku' => 'SAM-S21-128-DUAL', 'storage' => '128GB', 'sim_type' => 'Dual SIM', 'color' => 'Phantom Gray', 'price' => 42000, 'stock_quantity' => 10],
                    ['sku' => 'SAM-S21-256-DUAL', 'storage' => '256GB', 'sim_type' => 'Dual SIM', 'color' => 'Phantom White', 'price' => 47500, 'stock_quantity' => 6],
                ],
                'specs' => [
                    ['spec_name' => 'Display', 'spec_value' => '6.2" Dynamic AMOLED 120Hz', 'spec_group' => 'Display'],
                    ['spec_name' => 'Chip', 'spec_value' => 'Exynos 2100', 'spec_group' => 'Performance'],
                    ['spec_name' => 'Camera', 'spec_value' => '64MP Triple Camera', 'spec_group' => 'Camera'],
                    ['spec_name' => 'Battery', 'spec_value' => '4000mAh', 'spec_group' => 'Battery'],
                ],
                'advantages' => ['More Storage (256GB)', '120Hz Display', 'Expandable Storage', 'Great Value'],
            ],
            [
                'brand_id' => $apple->id,
                'category_id' => $computers->id,
                'name' => 'MacBook Pro M2',
                'slug' => 'macbook-pro-m2',
                'base_sku' => 'MBP-M2',
                'description' => 'MacBook Pro with M2 chip, delivering extraordinary performance and up to 20 hours of battery life.',
                'short_description' => 'Supercharged for pros.',
                'condition' => 'new',
                'base_price' => 185000,
                'original_price' => 210000,
                'is_active' => true,
                'featured' => true,
                'stock_status' => 'in_stock',
                'variants' => [
                    ['sku' => 'MBP-M2-256-SG', 'storage' => '256GB', 'sim_type' => null, 'color' => 'Space Gray', 'price' => 185000, 'stock_quantity' => 4],
                    ['sku' => 'MBP-M2-512-SL', 'storage' => '512GB', 'sim_type' => null, 'color' => 'Silver', 'price' => 215000, 'stock_quantity' => 3],
                    ['sku' => 'MBP-M2-1TB-SG', 'storage' => '1TB', 'sim_type' => null, 'color' => 'Space Gray', 'price' => 260000, 'stock_quantity' => 2],
                ],
                'specs' => [
                    ['spec_name' => 'Display', 'spec_value' => '13.3" Retina Display', 'spec_group' => 'Display'],
                    ['spec_name' => 'Chip', 'spec_value' => 'Apple M2', 'spec_group' => 'Performance'],
                    ['spec_name' => 'RAM', 'spec_value' => '16GB Unified Memory', 'spec_group' => 'Performance'],
                    ['spec_name' => 'Battery', 'spec_value' => 'Up to 20 hours', 'spec_group' => 'Battery'],
                ],
                'advantages' => ['M2 Chip Performance', '20-hour Battery Life', 'Retina Display', 'macOS Ecosystem', 'Silent Operation'],
            ],
            [
                'brand_id' => $apple->id,
                'category_id' => $phones->id,
                'name' => 'iPhone 12',
                'slug' => 'iphone-12',
                'base_sku' => 'IPH-12',
                'description' => 'iPhone 12 with A14 Bionic, Super Retina XDR display, and Ceramic Shield front cover.',
                'short_description' => 'Blast past fast.',
                'condition' => 'ex-uk',
                'base_price' => 35000,
                'is_active' => true,
                'featured' => false,
                'stock_status' => 'in_stock',
                'variants' => [
                    ['sku' => 'IPH-12-64-PHY', 'storage' => '64GB', 'sim_type' => 'Physical', 'color' => 'Black', 'price' => 32000, 'stock_quantity' => 15],
                    ['sku' => 'IPH-12-128-PHY', 'storage' => '128GB', 'sim_type' => 'Physical', 'color' => 'White', 'price' => 35000, 'stock_quantity' => 10],
                ],
                'specs' => [
                    ['spec_name' => 'Display', 'spec_value' => '6.1" Super Retina XDR', 'spec_group' => 'Display'],
                    ['spec_name' => 'Chip', 'spec_value' => 'A14 Bionic', 'spec_group' => 'Performance'],
                    ['spec_name' => 'Camera', 'spec_value' => '12MP Dual Camera', 'spec_group' => 'Camera'],
                    ['spec_name' => 'Battery', 'spec_value' => '2815mAh', 'spec_group' => 'Battery'],
                ],
                'advantages' => ['A14 Bionic Chip', 'Ceramic Shield', '5G Capable', 'Affordable Entry'],
            ],
        ];

        foreach ($products as $productData) {
            $variants = $productData['variants'];
            $specs = $productData['specs'];
            $advantages = $productData['advantages'];
            unset($productData['variants'], $productData['specs'], $productData['advantages']);

            $product = Product::firstOrCreate(['slug' => $productData['slug']], $productData);

            foreach ($variants as $variant) {
                ProductVariant::firstOrCreate(['sku' => $variant['sku']], array_merge($variant, ['product_id' => $product->id]));
            }

            foreach ($specs as $i => $spec) {
                ProductSpecification::firstOrCreate(
                    ['product_id' => $product->id, 'spec_name' => $spec['spec_name']],
                    array_merge($spec, ['product_id' => $product->id, 'sort_order' => $i])
                );
            }

            foreach ($advantages as $i => $adv) {
                ProductAdvantage::firstOrCreate(
                    ['product_id' => $product->id, 'advantage' => $adv],
                    ['product_id' => $product->id, 'advantage' => $adv, 'sort_order' => $i]
                );
            }
        }

        // AI Knowledge Base entries
        $knowledge = [
            [
                'topic' => 'iPhone vs Samsung camera comparison',
                'content' => 'iPhone cameras excel in video recording with Cinematic mode and ProRes support. Samsung cameras offer higher megapixel counts (up to 200MP on Ultra models) and more versatile zoom capabilities. For casual photography, both are excellent. For video, iPhone leads. For zoom and night mode, Samsung has the edge.',
                'keywords' => json_encode(['camera', 'photo', 'video', 'quality', 'comparison']),
                'category' => 'comparison',
                'is_active' => true,
            ],
            [
                'topic' => 'Best budget phone under 50000 KES',
                'content' => 'For under KSh 50,000, the best options are: iPhone 13 128GB (KSh 46,000) for iOS users, or Samsung S21 256GB (KSh 47,500) for Android users. The iPhone 13 offers better processor performance and video, while the Samsung S21 offers more storage, 120Hz display, and expandable storage.',
                'keywords' => json_encode(['budget', 'affordable', 'cheap', 'under 50000', 'best phone']),
                'category' => 'recommendation',
                'is_active' => true,
            ],
            [
                'topic' => 'New vs Ex-UK phones',
                'content' => 'Ex-UK phones are pre-owned devices imported from the UK market. They are typically in excellent condition, thoroughly tested, and significantly cheaper than new devices. New phones come sealed with full manufacturer warranty. Ex-UK phones may have minor cosmetic wear but offer 30-50% savings. All our Ex-UK devices are tested and come with a 3-month warranty.',
                'keywords' => json_encode(['ex-uk', 'new', 'used', 'refurbished', 'condition', 'warranty']),
                'category' => 'general',
                'is_active' => true,
            ],
            [
                'topic' => 'MacBook vs Windows laptop comparison',
                'content' => 'MacBooks with M-series chips offer exceptional battery life (up to 20 hours), silent operation, and excellent build quality. Windows laptops offer more variety in price points, better gaming support, and more customization. For creative professionals, MacBook Pro is often preferred. For budget-conscious buyers or gamers, Windows laptops offer better value.',
                'keywords' => json_encode(['macbook', 'windows', 'laptop', 'computer', 'comparison']),
                'category' => 'comparison',
                'is_active' => true,
            ],
            [
                'topic' => 'Phone buying guide Kenya',
                'content' => 'When buying a phone in Kenya, consider: 1) Network compatibility (all major Kenyan networks supported), 2) SIM type (eSIM vs physical - most Kenyan carriers support both), 3) Storage needs (128GB minimum recommended), 4) Camera quality for your needs, 5) Battery life for Kenyan power situations. We offer M-Pesa payment, cash on delivery, and card payments.',
                'keywords' => json_encode(['kenya', 'buying guide', 'tips', 'mpesa', 'network', 'sim']),
                'category' => 'general',
                'is_active' => true,
            ],
        ];

        foreach ($knowledge as $entry) {
            AIKnowledgeBase::firstOrCreate(['topic' => $entry['topic']], $entry);
        }
    }
}
