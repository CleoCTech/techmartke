# TechMartKE Platform - Complete Design & Implementation Guide

> **Modern E-Commerce Platform for Phones & Computers**  
> Budget-based AI-powered product comparison system

---

## 📋 Table of Contents

1. [Project Overview](#project-overview)
2. [Tech Stack](#tech-stack)
3. [Database Design](#database-design)
4. [Application Architecture](#application-architecture)
5. [Design Flow](#design-flow)
6. [Feature Specifications](#feature-specifications)
7. [Implementation Roadmap](#implementation-roadmap)
8. [API Endpoints](#api-endpoints)
9. [Frontend Components](#frontend-components)
10. [Deployment Guide](#deployment-guide)

---

## 🎯 Project Overview

**TechMartKE** is a modern e-commerce platform specializing in:
- **Phones:** iPhone, Samsung Galaxy, Google Pixel
- **Computers:** New and Ex-UK (MacBooks, Windows Laptops, Desktops)

### Unique Features

1. **Budget-Based Comparison**
   - User enters budget (e.g., KSh 48,000)
   - System finds phones/computers within ±5% range
   - AI-powered side-by-side comparison
   - Highlights advantages of each product

2. **AI Knowledge Base**
   - Interactive chat assistant
   - Product recommendations
   - Feature explanations
   - Purchase guidance

3. **Image Zoom Functionality**
   - Click any product image to zoom
   - High-resolution product photos
   - Multiple angles gallery

4. **Smart Product Variants**
   - Storage options (128GB, 256GB, 512GB, 1TB)
   - SIM types (eSIM, Physical SIM, Dual SIM)
   - Condition (New, Ex-UK, Refurbished)

5. **Bulk Price Upload**
   - Paste formatted text with prices
   - Automatic product/variant creation
   - SKU auto-generation
   - Audit trail logging

---

## 🛠 Tech Stack

### Backend
- **Framework:** Laravel 10.x
- **Language:** PHP 8.2+
- **Database:** MySQL 8.0
- **Cache:** Redis 7
- **Queue:** Laravel Queues (Redis driver)
- **Storage:** AWS S3 / DigitalOcean Spaces

### Frontend
- **Framework:** Vue.js 3 (Composition API - Script Setup)
- **Bridge:** Inertia.js 1.0
- **Styling:** Tailwind CSS 3
- **Build Tool:** Vite
- **State Management:** Pinia (optional - Inertia handles most state)
- **Icons:** Heroicons / Lucide Icons

### Additional Tools
- **Image Processing:** Intervention/Image
- **Email:** Laravel Mail (SendGrid/SMTP)
- **Notifications:** Laravel Notifications
- **Search:** Laravel Scout + Meilisearch (optional)

---

## 🗄 Database Design

### Core Tables

#### 1. **brands**
```sql
CREATE TABLE brands (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) UNIQUE NOT NULL,           -- Apple, Samsung, Google, Dell, HP
    slug VARCHAR(100) UNIQUE NOT NULL,
    logo_url VARCHAR(500),
    is_active BOOLEAN DEFAULT TRUE,
    sort_order INT DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_slug (slug),
    INDEX idx_is_active (is_active)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
```

#### 2. **categories**
```sql
CREATE TABLE categories (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    parent_id BIGINT UNSIGNED NULL,
    name VARCHAR(100) NOT NULL,                   -- Phones, Computers, Accessories
    slug VARCHAR(100) UNIQUE NOT NULL,
    description TEXT,
    image_url VARCHAR(500),
    sort_order INT DEFAULT 0,
    is_active BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (parent_id) REFERENCES categories(id) ON DELETE SET NULL,
    INDEX idx_parent_id (parent_id),
    INDEX idx_slug (slug)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
```

#### 3. **products**
```sql
CREATE TABLE products (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    brand_id BIGINT UNSIGNED NOT NULL,
    category_id BIGINT UNSIGNED NOT NULL,
    name VARCHAR(255) NOT NULL,                   -- iPhone 14 Pro, Samsung S23 Ultra
    slug VARCHAR(255) UNIQUE NOT NULL,
    base_sku VARCHAR(100) UNIQUE NOT NULL,        -- IPH-14-PRO, SAM-S23-ULTRA
    description TEXT,
    short_description VARCHAR(500),
    condition ENUM('new', 'ex-uk', 'refurbished') DEFAULT 'new',
    base_price DECIMAL(10,2) NOT NULL,            -- Base price without variants
    original_price DECIMAL(10,2),                 -- For showing discounts
    cost_price DECIMAL(10,2),                     -- Internal cost
    is_active BOOLEAN DEFAULT TRUE,
    featured BOOLEAN DEFAULT FALSE,
    stock_status ENUM('in_stock', 'out_of_stock', 'pre_order') DEFAULT 'in_stock',
    meta_title VARCHAR(255),
    meta_description TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (brand_id) REFERENCES brands(id) ON DELETE CASCADE,
    FOREIGN KEY (category_id) REFERENCES categories(id) ON DELETE CASCADE,
    INDEX idx_brand_id (brand_id),
    INDEX idx_category_id (category_id),
    INDEX idx_slug (slug),
    INDEX idx_condition (condition),
    INDEX idx_featured (featured),
    FULLTEXT idx_search (name, description)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
```

#### 4. **product_variants**
```sql
CREATE TABLE product_variants (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    product_id BIGINT UNSIGNED NOT NULL,
    sku VARCHAR(100) UNIQUE NOT NULL,             -- IPH-14-PRO-256GB-ESIM
    storage VARCHAR(50),                          -- 128GB, 256GB, 512GB, 1TB
    sim_type VARCHAR(50),                         -- eSIM, Physical, Dual
    color VARCHAR(50),                            -- Space Gray, Silver, Gold
    price DECIMAL(10,2) NOT NULL,
    cost_price DECIMAL(10,2),
    stock_quantity INT DEFAULT 0,
    is_active BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE,
    INDEX idx_product_id (product_id),
    INDEX idx_sku (sku),
    INDEX idx_is_active (is_active)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
```

#### 5. **product_images**
```sql
CREATE TABLE product_images (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    product_id BIGINT UNSIGNED NOT NULL,
    image_url VARCHAR(500) NOT NULL,
    thumbnail_url VARCHAR(500),
    zoom_url VARCHAR(500),                        -- High-res for zoom
    alt_text VARCHAR(255),
    sort_order INT DEFAULT 0,
    is_primary BOOLEAN DEFAULT FALSE,
    image_type ENUM('main', 'gallery', 'zoom', 'thumbnail') DEFAULT 'gallery',
    width INT,
    height INT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE,
    INDEX idx_product_id (product_id),
    INDEX idx_is_primary (is_primary)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
```

#### 6. **product_specifications**
```sql
CREATE TABLE product_specifications (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    product_id BIGINT UNSIGNED NOT NULL,
    spec_name VARCHAR(100) NOT NULL,              -- Display, Camera, Battery, RAM
    spec_value TEXT NOT NULL,                     -- 6.1" Super Retina XDR
    spec_group VARCHAR(50),                       -- Display, Performance, Camera
    sort_order INT DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE,
    INDEX idx_product_id (product_id),
    INDEX idx_spec_group (spec_group)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
```

#### 7. **product_advantages**
```sql
CREATE TABLE product_advantages (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    product_id BIGINT UNSIGNED NOT NULL,
    advantage TEXT NOT NULL,                      -- "A15 Bionic Chip", "iOS Ecosystem"
    sort_order INT DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE,
    INDEX idx_product_id (product_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
```

#### 8. **product_comparisons**
```sql
CREATE TABLE product_comparisons (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    product_1_id BIGINT UNSIGNED NOT NULL,
    product_2_id BIGINT UNSIGNED NOT NULL,
    comparison_data JSON,                         -- Pre-computed comparison
    similarity_score DECIMAL(5,2),                -- 0-100 similarity
    price_difference DECIMAL(10,2),
    category_winner VARCHAR(100),                 -- Camera, Battery, Performance
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (product_1_id) REFERENCES products(id) ON DELETE CASCADE,
    FOREIGN KEY (product_2_id) REFERENCES products(id) ON DELETE CASCADE,
    INDEX idx_products (product_1_id, product_2_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
```

#### 9. **ai_knowledge_base**
```sql
CREATE TABLE ai_knowledge_base (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    topic VARCHAR(255) NOT NULL,                  -- "iPhone vs Samsung", "Best camera phone"
    content TEXT NOT NULL,
    keywords JSON,                                -- For semantic search
    category VARCHAR(100),
    is_active BOOLEAN DEFAULT TRUE,
    usage_count INT DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_topic (topic),
    INDEX idx_category (category),
    FULLTEXT idx_content (topic, content)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
```

#### 10. **bulk_price_uploads**
```sql
CREATE TABLE bulk_price_uploads (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    uploaded_by BIGINT UNSIGNED NOT NULL,         -- Admin user ID
    raw_text TEXT NOT NULL,                       -- Original pasted text
    parsed_data JSON,                             -- Parsed products/variants
    status ENUM('pending', 'processing', 'completed', 'failed') DEFAULT 'pending',
    products_created INT DEFAULT 0,
    variants_created INT DEFAULT 0,
    errors JSON,                                  -- Validation/parsing errors
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (uploaded_by) REFERENCES users(id) ON DELETE CASCADE,
    INDEX idx_uploaded_by (uploaded_by),
    INDEX idx_status (status)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
```

#### 11. **customers**
```sql
CREATE TABLE customers (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    email VARCHAR(255) UNIQUE NOT NULL,
    phone VARCHAR(20),
    password VARCHAR(255),
    email_verified_at TIMESTAMP NULL,
    preferences JSON,                             -- Budget range, preferred brands
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_email (email)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
```

#### 12. **orders**
```sql
CREATE TABLE orders (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    customer_id BIGINT UNSIGNED NOT NULL,
    order_number VARCHAR(50) UNIQUE NOT NULL,     -- ORD-2025-00001
    total_amount DECIMAL(10,2) NOT NULL,
    status ENUM('pending', 'confirmed', 'processing', 'shipped', 'delivered', 'cancelled') DEFAULT 'pending',
    payment_status ENUM('pending', 'paid', 'failed', 'refunded') DEFAULT 'pending',
    payment_method VARCHAR(50),                   -- M-Pesa, Card, Cash
    payment_reference VARCHAR(255),
    shipping_address TEXT,
    billing_address TEXT,
    customer_notes TEXT,
    admin_notes TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (customer_id) REFERENCES customers(id) ON DELETE CASCADE,
    INDEX idx_customer_id (customer_id),
    INDEX idx_order_number (order_number),
    INDEX idx_status (status)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
```

#### 13. **order_items**
```sql
CREATE TABLE order_items (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    order_id BIGINT UNSIGNED NOT NULL,
    product_id BIGINT UNSIGNED NOT NULL,
    variant_id BIGINT UNSIGNED,
    product_name VARCHAR(255) NOT NULL,           -- Snapshot at time of order
    variant_details VARCHAR(255),                 -- "256GB eSIM"
    quantity INT NOT NULL,
    unit_price DECIMAL(10,2) NOT NULL,
    subtotal DECIMAL(10,2) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (order_id) REFERENCES orders(id) ON DELETE CASCADE,
    FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE,
    FOREIGN KEY (variant_id) REFERENCES product_variants(id) ON DELETE SET NULL,
    INDEX idx_order_id (order_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
```

#### 14. **notifications**
```sql
CREATE TABLE notifications (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    user_id BIGINT UNSIGNED NOT NULL,
    type VARCHAR(100) NOT NULL,                   -- order_received, low_stock
    title VARCHAR(255) NOT NULL,
    message TEXT NOT NULL,
    data JSON,
    is_read BOOLEAN DEFAULT FALSE,
    read_at TIMESTAMP NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    INDEX idx_user_id (user_id),
    INDEX idx_is_read (is_read)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
```

#### 15. **search_history**
```sql
CREATE TABLE search_history (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    customer_id BIGINT UNSIGNED,
    search_query VARCHAR(255) NOT NULL,
    filters JSON,                                 -- Brand, price range, category
    results_count INT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (customer_id) REFERENCES customers(id) ON DELETE SET NULL,
    INDEX idx_customer_id (customer_id),
    INDEX idx_search_query (search_query)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
```

---

## 🏗 Application Architecture

### Folder Structure

```
techmart-ke/
├── app/
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── Admin/
│   │   │   │   ├── DashboardController.php
│   │   │   │   ├── ProductController.php
│   │   │   │   ├── OrderController.php
│   │   │   │   ├── BulkUploadController.php
│   │   │   │   └── NotificationController.php
│   │   │   ├── Customer/
│   │   │   │   ├── HomeController.php
│   │   │   │   ├── ProductController.php
│   │   │   │   ├── ComparisonController.php
│   │   │   │   ├── CartController.php
│   │   │   │   ├── CheckoutController.php
│   │   │   │   └── AIAssistantController.php
│   │   │   └── Auth/
│   │   │       ├── LoginController.php
│   │   │       └── RegisterController.php
│   │   └── Middleware/
│   │       ├── AdminMiddleware.php
│   │       └── CustomerMiddleware.php
│   ├── Models/
│   │   ├── Brand.php
│   │   ├── Category.php
│   │   ├── Product.php
│   │   ├── ProductVariant.php
│   │   ├── ProductImage.php
│   │   ├── ProductSpecification.php
│   │   ├── ProductAdvantage.php
│   │   ├── ProductComparison.php
│   │   ├── AIKnowledgeBase.php
│   │   ├── BulkPriceUpload.php
│   │   ├── Customer.php
│   │   ├── Order.php
│   │   ├── OrderItem.php
│   │   └── Notification.php
│   ├── Services/
│   │   ├── BulkUploadService.php
│   │   ├── ComparisonService.php
│   │   ├── AIAssistantService.php
│   │   ├── NotificationService.php
│   │   └── OrderService.php
│   └── Jobs/
│       ├── ProcessBulkUpload.php
│       └── SendOrderNotification.php
├── database/
│   ├── migrations/
│   └── seeders/
├── resources/
│   ├── js/
│   │   ├── Pages/
│   │   │   ├── Admin/
│   │   │   │   ├── Dashboard.vue
│   │   │   │   ├── Products/
│   │   │   │   │   ├── Index.vue
│   │   │   │   │   ├── Create.vue
│   │   │   │   │   ├── Edit.vue
│   │   │   │   │   └── BulkUpload.vue
│   │   │   │   ├── Orders/
│   │   │   │   │   ├── Index.vue
│   │   │   │   │   └── Show.vue
│   │   │   │   └── Settings.vue
│   │   │   ├── Customer/
│   │   │   │   ├── Home.vue
│   │   │   │   ├── Products/
│   │   │   │   │   ├── Index.vue
│   │   │   │   │   └── Show.vue
│   │   │   │   ├── Comparison.vue
│   │   │   │   ├── Cart.vue
│   │   │   │   └── Checkout.vue
│   │   │   └── Auth/
│   │   │       ├── Login.vue
│   │   │       └── Register.vue
│   │   ├── Layouts/
│   │   │   ├── AdminLayout.vue
│   │   │   ├── CustomerLayout.vue
│   │   │   └── GuestLayout.vue
│   │   ├── Components/
│   │   │   ├── Admin/
│   │   │   │   ├── Navbar.vue
│   │   │   │   ├── Sidebar.vue
│   │   │   │   └── NotificationBell.vue
│   │   │   ├── Customer/
│   │   │   │   ├── Header.vue
│   │   │   │   ├── Footer.vue
│   │   │   │   ├── ProductCard.vue
│   │   │   │   ├── ComparisonModal.vue
│   │   │   │   ├── ImageZoom.vue
│   │   │   │   └── AIChat.vue
│   │   │   └── Shared/
│   │   │       ├── Button.vue
│   │   │       ├── Input.vue
│   │   │       └── Modal.vue
│   │   ├── app.js
│   │   └── bootstrap.js
│   └── views/
│       └── app.blade.php
├── routes/
│   ├── web.php
│   ├── admin.php
│   └── customer.php
└── public/
    └── build/
```

---

## 🎨 Design Flow

### 1. Customer Journey

#### **Homepage (Landing)**
```
┌─────────────────────────────────────────────┐
│         TECHMART KE LOGO + NAVIGATION       │
│    [Phones] [Computers] [New] [Ex-UK]      │
│              [Cart] [Login]                 │
└─────────────────────────────────────────────┘
│
│  ┌──────────────────────────────────────┐
│  │   FIND YOUR PERFECT DEVICE           │
│  │   Smart AI-powered comparison        │
│  │                                       │
│  │   Your Budget (KES): [48,000]        │
│  │   [COMPARE NOW]                      │
│  └──────────────────────────────────────┘
│
│  ┌──────────────────────────────────────┐
│  │   FEATURED PRODUCTS                  │
│  │   ┌────────┐ ┌────────┐ ┌────────┐  │
│  │   │iPhone  │ │Samsung │ │MacBook│  │
│  │   │14 Pro  │ │S23     │ │Pro M2 │  │
│  │   │81,000  │ │120,000 │ │185,000│  │
│  │   └────────┘ └────────┘ └────────┘  │
│  └──────────────────────────────────────┘
│
│  [AI CHAT BUTTON - Bottom Right Float]
└─────────────────────────────────────────────
│              FOOTER                         │
└─────────────────────────────────────────────┘
```

#### **Budget Comparison Flow**
```
User enters: 48,000 KES
       ↓
System searches: 45,600 - 50,400 (±5%)
       ↓
Shows comparison modal:
┌───────────────────────────────────────────────┐
│  BUDGET COMPARISON - KSh 48,000               │
├───────────────────────────────────────────────┤
│  ┌──────────────┐     ┌──────────────┐       │
│  │ iPhone 13    │     │ Samsung S21  │       │
│  │ 128GB        │     │ 256GB        │       │
│  │              │     │              │       │
│  │ KSh 46,000   │     │ KSh 47,500   │       │
│  │              │     │              │       │
│  │ ✓ A15 Chip   │     │ ✓ 256GB      │       │
│  │ ✓ iOS        │     │ ✓ 120Hz      │       │
│  │ ✓ Camera     │     │ ✓ Expandable │       │
│  │              │     │              │       │
│  │ Display: 6.1"│     │ Display: 6.2"│       │
│  │ Camera: 12MP │     │ Camera: 64MP │       │
│  │ Battery: All │     │ Battery: 4000│       │
│  └──────────────┘     └──────────────┘       │
│                                               │
│  AI RECOMMENDATION:                           │
│  Choose iPhone 13 for iOS ecosystem...        │
└───────────────────────────────────────────────┘
```

#### **Product Detail Page**
```
┌─────────────────────────────────────────────┐
│  BREADCRUMB: Home > Phones > iPhone 14 Pro  │
├─────────────────────────────────────────────┤
│  ┌──────────────┐   IPHONE 14 PRO           │
│  │              │   KSh 81,000              │
│  │  [IMAGE]     │                           │
│  │   [ZOOM]     │   256GB • eSIM • Ex-UK    │
│  │              │                           │
│  │ [THUMB][...]  │   [SELECT STORAGE]        │
│  └──────────────┘   [128GB] [256GB] [512GB] │
│                                              │
│  SPECIFICATIONS:                             │
│  • Display: 6.1" Super Retina XDR           │
│  • Camera: 48MP Main + 12MP Ultra Wide      │
│  • Battery: All-day battery life            │
│                                              │
│  [ADD TO CART]  [BUY NOW]                   │
└─────────────────────────────────────────────┘
```

#### **AI Chat Interface**
```
┌─────────────────────────────────┐
│  AI ASSISTANT            [X]    │
├─────────────────────────────────┤
│                                 │
│  💬 Hi! I can help you find    │
│     the perfect phone...        │
│                                 │
│             I need a phone      │
│             for 50,000 KES  💭  │
│                                 │
│  💬 Great! For 50,000 KES,     │
│     I recommend...              │
│                                 │
├─────────────────────────────────┤
│  [Ask me anything...]  [SEND]   │
└─────────────────────────────────┘
```

---

### 2. Admin Dashboard Journey

#### **Admin Dashboard**
```
┌─────────────────────────────────────────────┐
│  SIDEBAR          DASHBOARD                 │
├──────────┬──────────────────────────────────┤
│          │  STATS CARDS:                    │
│ Dashboard│  ┌────────┐ ┌────────┐ ┌────────┐│
│ Products │  │ Total  │ │Orders  │ │Revenue││
│ Orders   │  │ 234    │ │ 45     │ │125K  ││
│ Upload   │  └────────┘ └────────┘ └────────┘│
│ Settings │                                   │
│          │  RECENT ORDERS:                   │
│          │  ┌────────────────────────────┐  │
│          │  │ #ORD-001 | iPhone 14 Pro  │  │
│          │  │ KSh 81,000 | Pending      │  │
│          │  └────────────────────────────┘  │
│          │                                   │
│  🔔 (3)  │  LOW STOCK ALERTS:               │
│          │  • iPhone 13 128GB (2 left)      │
└──────────┴──────────────────────────────────┘
```

#### **Bulk Upload Interface**
```
┌─────────────────────────────────────────────┐
│  BULK PRICE UPLOAD                          │
├─────────────────────────────────────────────┤
│  Paste your price list here:                │
│  ┌─────────────────────────────────────┐   │
│  │ 12 128GB - 35,000/-                 │   │
│  │ 12 Pro 128GB - 43,000/-             │   │
│  │ 14 Pro Max 256GB eSIM - 81,000/-    │   │
│  │ ...                                  │   │
│  └─────────────────────────────────────┘   │
│                                             │
│  [PARSE & PREVIEW]                          │
│                                             │
│  PREVIEW:                                   │
│  ✓ iPhone 12 128GB - KSh 35,000            │
│  ✓ iPhone 12 Pro 128GB - KSh 43,000        │
│  ✓ iPhone 14 Pro Max 256GB eSIM - 81,000   │
│                                             │
│  [CANCEL]  [CONFIRM UPLOAD]                 │
└─────────────────────────────────────────────┘
```

---

## ⚙️ Feature Specifications

### 1. Budget-Based Comparison

**How It Works:**

1. **Input:** User enters budget (e.g., 48,000 KES)
2. **Search Range:** System calculates ±5% (45,600 - 50,400)
3. **Query:** Find products within range
4. **Filter:** Group by category (phones, computers)
5. **Comparison:** Generate side-by-side cards
6. **AI Analysis:** Provide recommendation based on:
   - User preferences (if logged in)
   - Product specifications
   - Value for money
   - Use case

**Algorithm:**
```php
// ComparisonService.php
public function compareByBudget($budget, $tolerance = 0.05)
{
    $minPrice = $budget * (1 - $tolerance);
    $maxPrice = $budget * (1 + $tolerance);
    
    $products = Product::with(['variants', 'specifications', 'advantages', 'images'])
        ->whereBetween('base_price', [$minPrice, $maxPrice])
        ->where('is_active', true)
        ->get();
    
    return $this->generateComparison($products);
}
```

---

### 2. Bulk Price Upload

**Text Format:**
```
12 128GB - 35,000/-
12 Pro 128GB - 43,000/-
12 Pro Max 256GB - 56,000/-
14 Pro Max 256GB eSIM - 81,000/-
```

**Parsing Logic:**
```php
// BulkUploadService.php
public function parseText($text)
{
    $lines = explode("\n", $text);
    $parsed = [];
    
    foreach ($lines as $line) {
        // Pattern: [model] [storage] [sim?] - [price]
        preg_match('/^(\d+)\s*(Pro|Pro Max)?\s*(\d+GB)\s*(eSIM|Physical)?\s*-\s*([\d,]+)/', $line, $matches);
        
        if ($matches) {
            $parsed[] = [
                'model' => trim($matches[1] . ' ' . ($matches[2] ?? '')),
                'storage' => $matches[3],
                'sim_type' => $matches[4] ?? 'Physical',
                'price' => (float) str_replace(',', '', $matches[5])
            ];
        }
    }
    
    return $parsed;
}

public function createProductsAndVariants($parsed, $brandName = 'Apple')
{
    foreach ($parsed as $item) {
        // Find or create product
        $product = Product::firstOrCreate([
            'name' => "iPhone {$item['model']}",
            'brand_id' => Brand::where('name', $brandName)->first()->id,
            // ... other fields
        ]);
        
        // Create variant
        ProductVariant::create([
            'product_id' => $product->id,
            'sku' => $this->generateSKU($product->name, $item),
            'storage' => $item['storage'],
            'sim_type' => $item['sim_type'],
            'price' => $item['price']
        ]);
    }
}

private function generateSKU($productName, $variant)
{
    // IPH-14-PRO-256GB-ESIM
    $parts = explode(' ', $productName);
    $prefix = strtoupper(substr($parts[0], 0, 3)); // IPH
    $model = str_replace(' ', '-', $parts[1] ?? ''); // 14
    
    return "{$prefix}-{$model}-{$variant['storage']}-" . strtoupper($variant['sim_type']);
}
```

---

### 3. AI Assistant

**Knowledge Base Structure:**
```json
{
  "topic": "iPhone vs Samsung camera comparison",
  "content": "iPhone cameras excel in video recording...",
  "keywords": ["camera", "photo", "video", "quality"],
  "category": "comparison"
}
```

**Chat Flow:**
```php
// AIAssistantController.php
public function chat(Request $request)
{
    $message = $request->input('message');
    
    // Search knowledge base
    $knowledge = AIKnowledgeBase::whereRaw(
        "MATCH(topic, content) AGAINST(? IN NATURAL LANGUAGE MODE)",
        [$message]
    )->first();
    
    if ($knowledge) {
        return response()->json([
            'reply' => $knowledge->content,
            'suggestions' => $this->getRelatedProducts($message)
        ]);
    }
    
    // Fallback to generic response
    return response()->json([
        'reply' => 'I can help you find products within your budget...'
    ]);
}
```

---

### 4. Image Zoom Functionality

**Implementation:**

1. **Three Image Sizes:**
   - Thumbnail: 150x150px (listing grid)
   - Main: 800x800px (product page)
   - Zoom: 2000x2000px (modal zoom)

2. **Storage:**
```php
// When uploading image
$image = $request->file('image');

ProductImage::create([
    'product_id' => $product->id,
    'image_url' => Storage::url($image->storeAs('products/main', $filename)),
    'thumbnail_url' => $this->generateThumbnail($image, 150),
    'zoom_url' => $this->generateZoom($image, 2000),
]);
```

3. **Frontend:**
```vue
<!-- ProductShow.vue -->
<template>
  <div class="product-images">
    <img 
      :src="selectedImage.image_url" 
      @click="showZoom(selectedImage.zoom_url)"
      class="cursor-zoom-in"
    />
    
    <!-- Zoom Modal -->
    <ImageZoomModal 
      v-if="zoomImage"
      :image="zoomImage"
      @close="zoomImage = null"
    />
  </div>
</template>
```

---

### 5. Order Notifications

**Notification Flow:**

```
New Order Created
       ↓
1. Database Notification (notifications table)
       ↓
2. Email to Admin (Laravel Mail)
       ↓
3. Dashboard Bell Icon Updates (Inertia reactive)
       ↓
4. Optional: SMS via Africa's Talking API
```

**Implementation:**
```php
// OrderService.php
public function createOrder($data)
{
    DB::transaction(function() use ($data) {
        $order = Order::create($data);
        
        // Create notification
        Notification::create([
            'user_id' => auth()->id(),
            'type' => 'order_received',
            'title' => 'New Order #' . $order->order_number,
            'message' => "New order received for KSh {$order->total_amount}",
            'data' => json_encode($order)
        ]);
        
        // Send email
        Mail::to(config('app.admin_email'))->send(
            new OrderReceivedMail($order)
        );
        
        return $order;
    });
}
```

---

## 🛣 Implementation Roadmap

### Phase 1: Foundation (Week 1-2)

**Backend:**
- ✅ Laravel 10 installation
- ✅ Database migrations (all 15 tables)
- ✅ Model relationships
- ✅ Authentication (admin + customer)
- ✅ Basic API routes

**Frontend:**
- ✅ Inertia.js + Vue 3 setup
- ✅ Tailwind CSS configuration
- ✅ Layout components (Admin, Customer, Guest)
- ✅ Basic navigation

**Deliverables:**
- Working authentication
- Database structure complete
- Admin can login to empty dashboard

---

### Phase 2: Admin Panel (Week 3-4)

**Features:**
- ✅ Admin dashboard with stats
- ✅ Product CRUD (Create, Read, Update, Delete)
- ✅ Product variants management
- ✅ Image upload with Intervention/Image
- ✅ Bulk price upload functionality
- ✅ Basic order management

**Components:**
```
Admin/
├── Dashboard.vue
├── Products/
│   ├── Index.vue (List products with filters)
│   ├── Create.vue (Add new product)
│   ├── Edit.vue (Edit existing product)
│   └── BulkUpload.vue (Paste text and upload)
└── Orders/
    ├── Index.vue (List orders)
    └── Show.vue (Order details)
```

**Deliverables:**
- Admin can add products manually
- Admin can bulk upload via text paste
- Product variants auto-created
- SKUs auto-generated

---

### Phase 3: Customer Frontend (Week 5-6)

**Features:**
- ✅ Homepage with featured products
- ✅ Product listing with filters
- ✅ Budget-based search
- ✅ Product detail page
- ✅ Image zoom modal
- ✅ Product comparison modal

**Components:**
```
Customer/
├── Home.vue (Landing page)
├── Products/
│   ├── Index.vue (Product grid with filters)
│   └── Show.vue (Product detail with zoom)
├── Comparison.vue (Side-by-side comparison)
└── Components/
    ├── ProductCard.vue
    ├── ComparisonModal.vue
    └── ImageZoom.vue
```

**Deliverables:**
- Beautiful homepage
- Product browsing
- Budget comparison works
- Image zoom functional

---

### Phase 4: Shopping & Checkout (Week 7-8)

**Features:**
- ✅ Shopping cart (session-based)
- ✅ Checkout process
- ✅ M-Pesa integration (optional)
- ✅ Order confirmation
- ✅ Email notifications

**Components:**
```
Customer/
├── Cart.vue (View cart items)
├── Checkout.vue (Enter details, pay)
└── OrderConfirmation.vue (Thank you page)
```

**Deliverables:**
- Users can add to cart
- Checkout flow complete
- Orders saved to database
- Admin gets email notification

---

### Phase 5: AI Assistant (Week 9)

**Features:**
- ✅ AI chat widget (floating button)
- ✅ Knowledge base seeding
- ✅ Chat interface
- ✅ Product recommendations

**Components:**
```
Customer/Components/
└── AIChat.vue (Floating chat widget)
```

**Deliverables:**
- Chat widget appears on all pages
- Responds to common questions
- Suggests products based on budget

---

### Phase 6: Polish & Deploy (Week 10)

**Tasks:**
- ✅ Performance optimization
- ✅ SEO metadata
- ✅ Mobile responsiveness
- ✅ Error handling
- ✅ Testing
- ✅ Deployment to VPS

**Deliverables:**
- Production-ready application
- Fast loading times
- Mobile-friendly
- Deployed and live

---

## 🔌 API Endpoints

### Public Endpoints (Customer)

```
GET  /                          - Homepage
GET  /products                  - Product listing
GET  /products/{slug}           - Product detail
GET  /products/compare          - Budget comparison
POST /cart/add                  - Add to cart
GET  /cart                      - View cart
POST /checkout                  - Process checkout
POST /ai/chat                   - AI assistant
```

### Admin Endpoints (Authenticated)

```
GET    /admin/dashboard         - Admin dashboard
GET    /admin/products          - List products
POST   /admin/products          - Create product
GET    /admin/products/{id}/edit - Edit product form
PUT    /admin/products/{id}     - Update product
DELETE /admin/products/{id}     - Delete product
POST   /admin/bulk-upload       - Bulk price upload
GET    /admin/orders            - List orders
GET    /admin/orders/{id}       - Order details
PATCH  /admin/orders/{id}/status - Update order status
GET    /admin/notifications     - Get notifications
POST   /admin/notifications/{id}/read - Mark as read
```

---

## 🧩 Frontend Components

### Key Vue Components

#### 1. **ProductCard.vue** (Reusable)
```vue
<script setup>
defineProps({
  product: Object,
  showCompare: Boolean
});
</script>

<template>
  <div class="product-card">
    <div class="relative h-64 bg-gray-100 overflow-hidden group">
      <img 
        :src="product.images[0]?.image_url" 
        :alt="product.name"
        class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500"
      />
      <div v-if="product.stock_status === 'in_stock'" 
           class="absolute top-4 right-4 bg-green-500 text-white px-3 py-1 rounded-full text-sm">
        In Stock
      </div>
    </div>
    
    <div class="p-6">
      <h4 class="text-xl font-bold mb-2">{{ product.name }}</h4>
      <p class="text-gray-600 text-sm mb-4">{{ product.condition }} • {{ product.variants[0]?.sim_type }}</p>
      
      <div class="flex items-center justify-between">
        <p class="text-3xl font-bold">KSh {{ product.base_price.toLocaleString() }}</p>
        <button class="px-4 py-2 bg-black text-white rounded-lg hover:bg-gray-800">
          View Details
        </button>
      </div>
    </div>
  </div>
</template>

<style scoped>
.product-card {
  @apply bg-white rounded-2xl overflow-hidden shadow-lg transition-all duration-300;
}

.product-card:hover {
  @apply transform -translate-y-2 shadow-2xl;
}
</style>
```

#### 2. **ComparisonModal.vue**
```vue
<script setup>
import { computed } from 'vue';

const props = defineProps({
  products: Array,
  budget: Number
});

const emit = defineEmits(['close']);
</script>

<template>
  <div class="fixed inset-0 bg-black bg-opacity-80 flex items-center justify-center p-4 z-50">
    <div class="bg-white rounded-2xl p-8 max-w-6xl w-full max-h-[90vh] overflow-auto">
      <div class="flex justify-between items-center mb-6">
        <h3 class="text-2xl font-bold">Budget Comparison - KSh {{ budget.toLocaleString() }}</h3>
        <button @click="emit('close')" class="text-gray-500 hover:text-black">
          <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
          </svg>
        </button>
      </div>

      <div class="grid md:grid-cols-2 gap-8">
        <div v-for="product in products" :key="product.id" class="border-2 border-black rounded-2xl p-6">
          <img 
            :src="product.images[0]?.image_url" 
            :alt="product.name"
            class="w-full h-64 object-cover rounded-xl mb-4"
          />
          
          <h4 class="text-2xl font-bold mb-2">{{ product.name }}</h4>
          <p class="text-3xl font-bold mb-4">KSh {{ product.base_price.toLocaleString() }}</p>
          
          <!-- Advantages -->
          <div class="space-y-3 mb-4">
            <div 
              v-for="advantage in product.advantages" 
              :key="advantage.id"
              class="bg-green-50 border border-green-200 rounded-lg p-3"
            >
              <p class="text-sm font-medium text-green-800">✓ {{ advantage.advantage }}</p>
            </div>
          </div>

          <!-- Specifications -->
          <div class="space-y-2 text-sm text-gray-600">
            <p v-for="spec in product.specifications" :key="spec.id">
              <strong>{{ spec.spec_name }}:</strong> {{ spec.spec_value }}
            </p>
          </div>
        </div>
      </div>

      <div class="mt-8 bg-gray-50 rounded-xl p-6">
        <h4 class="font-bold mb-3">AI Recommendation</h4>
        <p class="text-gray-700">
          Based on your budget of KSh {{ budget.toLocaleString() }}, both options offer excellent value...
        </p>
      </div>
    </div>
  </div>
</template>
```

#### 3. **AIChat.vue**
```vue
<script setup>
import { ref } from 'vue';
import { router } from '@inertiajs/vue3';

const isOpen = ref(false);
const messages = ref([
  { role: 'assistant', text: 'Hi! I can help you find the perfect phone or computer. What are you looking for?' }
]);
const userMessage = ref('');

const sendMessage = () => {
  if (!userMessage.value.trim()) return;
  
  messages.value.push({ role: 'user', text: userMessage.value });
  
  // Call AI endpoint
  router.post('/ai/chat', {
    message: userMessage.value
  }, {
    preserveState: true,
    onSuccess: (response) => {
      messages.value.push({ 
        role: 'assistant', 
        text: response.props.reply 
      });
    }
  });
  
  userMessage.value = '';
};
</script>

<template>
  <!-- Float Button -->
  <div 
    @click="isOpen = !isOpen"
    class="fixed bottom-8 right-8 w-16 h-16 bg-black text-white rounded-full flex items-center justify-center cursor-pointer shadow-xl hover:bg-gray-800 transition z-50"
  >
    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"/>
    </svg>
  </div>

  <!-- Chat Window -->
  <div 
    v-if="isOpen"
    class="fixed bottom-28 right-8 w-96 h-[500px] bg-white rounded-2xl shadow-2xl flex flex-col z-50"
  >
    <!-- Header -->
    <div class="bg-black text-white p-4 rounded-t-2xl flex justify-between items-center">
      <h4 class="font-bold">AI Assistant</h4>
      <button @click="isOpen = false" class="text-white hover:text-gray-300">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
        </svg>
      </button>
    </div>

    <!-- Messages -->
    <div class="flex-1 p-4 overflow-y-auto space-y-4">
      <div 
        v-for="(msg, index) in messages" 
        :key="index"
        :class="['flex', msg.role === 'user' ? 'justify-end' : 'justify-start']"
      >
        <div 
          :class="[
            'max-w-[80%] p-3 rounded-2xl',
            msg.role === 'user' 
              ? 'bg-black text-white rounded-br-sm' 
              : 'bg-gray-100 border border-gray-200 rounded-bl-sm'
          ]"
        >
          <p class="text-sm">{{ msg.text }}</p>
        </div>
      </div>
    </div>

    <!-- Input -->
    <div class="border-t border-gray-200 p-4">
      <div class="flex gap-2">
        <input
          v-model="userMessage"
          @keyup.enter="sendMessage"
          type="text"
          placeholder="Ask me anything..."
          class="flex-1 px-4 py-2.5 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-black"
        />
        <button 
          @click="sendMessage"
          class="px-5 py-2.5 bg-black text-white rounded-xl hover:bg-gray-800"
        >
          Send
        </button>
      </div>
    </div>
  </div>
</template>
```

---

## 🚀 Deployment Guide

### Server Requirements

- **Web Server:** Nginx
- **PHP:** 8.2+
- **Database:** MySQL 8.0
- **Cache:** Redis 7
- **Node.js:** 18+ (for building assets)
- **SSL:** Let's Encrypt

### Deployment Steps

#### 1. **VPS Setup**

```bash
# Update system
sudo apt update && sudo apt upgrade -y

# Install Nginx
sudo apt install nginx -y

# Install PHP 8.2
sudo apt install php8.2-fpm php8.2-mysql php8.2-redis php8.2-gd php8.2-xml php8.2-mbstring php8.2-curl -y

# Install MySQL
sudo apt install mysql-server -y

# Install Redis
sudo apt install redis-server -y

# Install Node.js 18
curl -fsSL https://deb.nodesource.com/setup_18.x | sudo -E bash -
sudo apt install -y nodejs

# Install Composer
curl -sS https://getcomposer.org/installer | php
sudo mv composer.phar /usr/local/bin/composer
```

#### 2. **Clone & Setup Project**

```bash
# Clone repository
cd /var/www
git clone https://github.com/yourusername/techmart-ke.git
cd techmart-ke

# Install dependencies
composer install --optimize-autoloader --no-dev
npm install
npm run build

# Setup environment
cp .env.example .env
php artisan key:generate

# Configure database in .env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_DATABASE=techmart_ke
DB_USERNAME=your_user
DB_PASSWORD=your_password

# Run migrations
php artisan migrate --force
php artisan db:seed

# Set permissions
sudo chown -R www-data:www-data /var/www/techmart-ke
sudo chmod -R 755 /var/www/techmart-ke/storage
```

#### 3. **Nginx Configuration**

```nginx
# /etc/nginx/sites-available/techmart-ke
server {
    listen 80;
    server_name techmart.ke www.techmart.ke;
    root /var/www/techmart-ke/public;

    add_header X-Frame-Options "SAMEORIGIN";
    add_header X-Content-Type-Options "nosniff";

    index index.php;

    charset utf-8;

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location = /favicon.ico { access_log off; log_not_found off; }
    location = /robots.txt  { access_log off; log_not_found off; }

    error_page 404 /index.php;

    location ~ \.php$ {
        fastcgi_pass unix:/var/run/php/php8.2-fpm.sock;
        fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
        include fastcgi_params;
    }

    location ~ /\.(?!well-known).* {
        deny all;
    }
}
```

```bash
# Enable site
sudo ln -s /etc/nginx/sites-available/techmart-ke /etc/nginx/sites-enabled/
sudo nginx -t
sudo systemctl restart nginx
```

#### 4. **SSL Certificate**

```bash
# Install Certbot
sudo apt install certbot python3-certbot-nginx -y

# Get SSL certificate
sudo certbot --nginx -d techmart.ke -d www.techmart.ke
```

#### 5. **Queue Worker (Systemd)**

```bash
# Create service file
sudo nano /etc/systemd/system/techmart-worker.service
```

```ini
[Unit]
Description=TechMart Queue Worker
After=network.target

[Service]
Type=simple
User=www-data
WorkingDirectory=/var/www/techmart-ke
ExecStart=/usr/bin/php /var/www/techmart-ke/artisan queue:work --sleep=3 --tries=3
Restart=always

[Install]
WantedBy=multi-user.target
```

```bash
# Enable and start
sudo systemctl enable techmart-worker
sudo systemctl start techmart-worker
```

#### 6. **Cron Jobs**

```bash
# Edit crontab
sudo crontab -e

# Add Laravel scheduler
* * * * * cd /var/www/techmart-ke && php artisan schedule:run >> /dev/null 2>&1
```

---

## 🎨 Design System

### Color Palette

```css
/* Tailwind Config Extension */
module.exports = {
  theme: {
    extend: {
      colors: {
        'primary': '#000000',      /* Black */
        'secondary': '#ffffff',    /* White */
        'accent': '#22c55e',       /* Green (for advantages) */
        'danger': '#ef4444',       /* Red (for alerts) */
        'gray': {
          50: '#f9fafb',
          100: '#f3f4f6',
          200: '#e5e7eb',
          300: '#d1d5db',
          400: '#9ca3af',
          500: '#6b7280',
          600: '#4b5563',
          700: '#374151',
          800: '#1f2937',
          900: '#111827',
        }
      }
    }
  }
}
```

### Typography

- **Headings:** Inter / SF Pro Display (Bold)
- **Body:** Inter / SF Pro Text (Regular)
- **Monospace:** JetBrains Mono (for SKUs, codes)

### Spacing

- **Cards:** 2rem (p-8)
- **Sections:** 4rem (py-16)
- **Grid Gap:** 2rem (gap-8)

---

## 📝 Additional Notes

### SKU Generation Logic

```
Format: [BRAND]-[MODEL]-[STORAGE]-[SIM]

Examples:
- IPH-14-PRO-256GB-ESIM
- SAM-S23-ULTRA-512GB-DUAL
- MAC-PRO-M2-512GB
```

### Price Formatting

Always display prices in Kenyan Shillings (KES):
```javascript
// Vue filter
const formatPrice = (price) => {
  return `KSh ${parseFloat(price).toLocaleString()}`;
};

// Usage: {{ formatPrice(48000) }}
// Output: KSh 48,000
```

### Image Optimization

- **Format:** WebP (with JPEG fallback)
- **Compression:** 80% quality
- **Lazy Loading:** Yes (native browser lazy loading)
- **CDN:** Optional (CloudFlare Images)

---

## 🔐 Security Considerations

1. **CSRF Protection:** Laravel handles automatically
2. **SQL Injection:** Use Eloquent ORM (parameterized queries)
3. **XSS Prevention:** Vue auto-escapes output
4. **File Upload:** Validate file types and sizes
5. **Rate Limiting:** Apply to API endpoints
6. **Password Hashing:** Bcrypt (Laravel default)
7. **HTTPS Only:** Force SSL in production

---

## 🧪 Testing Strategy

### Unit Tests
- Model relationships
- Service class methods
- SKU generation
- Price calculation

### Feature Tests
- User authentication
- Product CRUD
- Bulk upload
- Order creation

### Browser Tests (Laravel Dusk)
- Product comparison flow
- Add to cart
- Checkout process
- Admin dashboard

---

## 📈 Performance Optimization

1. **Database Indexing:** All foreign keys and search columns
2. **Query Optimization:** Eager loading relationships
3. **Caching:** Redis for product listings
4. **Asset Minification:** Vite handles automatically
5. **Image Optimization:** Intervention/Image with WebP
6. **CDN:** For static assets and images
7. **Lazy Loading:** Images and components

---

## 🎯 Success Metrics

### KPIs to Track

1. **Conversion Rate:** Orders / Visitors
2. **Average Order Value:** Total Revenue / Orders
3. **Budget Comparison Usage:** Comparisons / Product Views
4. **AI Assistant Engagement:** Chats / Sessions
5. **Mobile vs Desktop:** Traffic split
6. **Load Time:** < 3 seconds (target)
7. **Cart Abandonment:** Track and optimize

---

## 📞 Support & Maintenance

### Regular Tasks

**Daily:**
- Monitor server resources
- Check error logs
- Review new orders

**Weekly:**
- Update product prices
- Backup database
- Review analytics

**Monthly:**
- Security updates (Laravel, PHP)
- Performance optimization
- Feature improvements

---

## 🚀 Future Enhancements

### Phase 7+ (Post-Launch)

1. **Mobile Apps:** React Native / Flutter
2. **Multiple Payment Gateways:** Stripe, PayPal
3. **Wish List:** Save favorite products
4. **Price Alerts:** Notify when price drops
5. **Reviews & Ratings:** Customer feedback
6. **Advanced Search:** Filters by specs
7. **Recommendation Engine:** ML-based suggestions
8. **Multi-Language:** Swahili + English
9. **Social Sharing:** WhatsApp, Facebook integration
10. **Inventory Management:** Stock alerts, low stock warnings

---

## 📄 License

This project is proprietary software developed for TechMartKE.

---

## 👨‍💻 Developer Notes

**For AI Model Implementation:**

This README provides complete specifications for building TechMartKE. Key areas to focus on:

1. **Database First:** Create all 15 tables with proper relationships
2. **Bulk Upload:** Critical feature - prioritize the text parser
3. **Comparison Modal:** Core differentiator - make it beautiful
4. **Image Zoom:** Use proper modal with high-res images
5. **AI Chat:** Keep simple initially, enhance later
6. **Responsive Design:** Mobile-first approach
7. **Performance:** Optimize queries with eager loading
8. **Security:** Never trust user input, validate everything

**Testing Checklist:**
- [ ] Can admin bulk upload prices?
- [ ] Does SKU auto-generate correctly?
- [ ] Does budget comparison show relevant products?
- [ ] Can user zoom images?
- [ ] Does AI chat respond?
- [ ] Are notifications working?
- [ ] Is checkout flow smooth?

**Common Gotchas:**
- Remember to eager load relationships to avoid N+1 queries
- Always validate file uploads (type, size, malware)
- Use transactions for order creation (atomic operations)
- Cache product listings (invalidate on update)
- Index all foreign keys and search columns

---

**Good luck building TechMartKE! 🚀**
