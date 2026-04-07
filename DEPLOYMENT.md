# TechMart KE — Production Deployment Guide

## Server Requirements

- **PHP** ≥ 8.1 with extensions: BCMath, Ctype, Fileinfo, JSON, Mbstring, OpenSSL, PDO, Tokenizer, XML, GD/Imagick
- **MySQL** ≥ 8.0 (or MariaDB ≥ 10.3)
- **Composer** ≥ 2.x
- **Node.js** ≥ 18 + npm
- **Redis** (optional, for queues/cache)
- Web server: **Nginx** or **Apache**

## 1. Clone & Install

```bash
git clone https://github.com/CleoCTech/techmartke.git
cd techmartke

composer install --no-dev --optimize-autoloader
npm ci && npm run build
```

## 2. Environment Setup

Copy `.env.example` to `.env` and configure:

```bash
cp .env.example .env
php artisan key:generate
```

### Required `.env` Variables

```env
APP_NAME="TechMart KE"
APP_ENV=production
APP_KEY=                        # generated above
APP_DEBUG=false
APP_URL=https://techmartke.com

# Database
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=techmart_prod
DB_USERNAME=
DB_PASSWORD=

# Mail (for order/trade-in notifications)
MAIL_DRIVER=smtp
MAIL_HOST=
MAIL_PORT=465
MAIL_USERNAME=
MAIL_PASSWORD=
MAIL_ENCRYPTION=ssl
MAIL_FROM_ADDRESS=
MAIL_FROM_NAME="TechMart KE"

# Admin notifications
ADMIN_NOTIFICATION_EMAIL=dsldev00@gmail.com

# AI features (Anthropic Claude)
ANTHROPIC_API_KEY=

# Company info defaults
COMPANY_NAME="TechMart Kenya"
COMPANY_SHORT_NAME="TechMartKE"
WHATSAPP_PHONE=254700000000

# DO NOT seed sample products in production
SEED_SAMPLE_PRODUCTS=false
```

## 3. Database Setup

```bash
php artisan migrate --force
php artisan db:seed --force
```

This creates:
- ✅ Admin user: `admin@techmartke.com` (password: `password` — **change immediately**)
- ✅ Roles & permissions
- ✅ Company information (TechMart KE defaults)
- ✅ Brands (Apple, Samsung, Google, Dell, HP, Lenovo)
- ✅ Categories (Phones, Computers, with sub-categories)
- ✅ VIP tiers

## 4. Storage & Permissions

```bash
php artisan storage:link
chmod -R 775 storage bootstrap/cache
chown -R www-data:www-data storage bootstrap/cache
```

## 5. Optimize for Production

```bash
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan event:cache
```

## 6. First Login & Configuration

1. Visit `https://yourdomain.com/login`
2. Login with `admin@techmartke.com` / `password`
3. **Immediately change the password** in Account Settings
4. Go to **Store Setup → Company Info** and update phone, email, address
5. Add real products via **Products → Add Product** or **Bulk Upload**

## 7. Cron Job (for scheduled tasks)

Add to crontab:
```cron
* * * * * cd /var/www/techmartke && php artisan schedule:run >> /dev/null 2>&1
```

## 8. Web Server Configuration

### Nginx Example

```nginx
server {
    listen 80;
    server_name techmartke.com www.techmartke.com;
    root /var/www/techmartke/public;

    index index.php;

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location ~ \.php$ {
        fastcgi_pass unix:/var/run/php/php8.1-fpm.sock;
        fastcgi_index index.php;
        fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
        include fastcgi_params;
    }

    client_max_body_size 10M;
}
```

## Updates & Re-deploys

```bash
git pull origin master
composer install --no-dev --optimize-autoloader
npm ci && npm run build
php artisan migrate --force
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

## Default Admin Credentials

- **Email**: `admin@techmartke.com`
- **Password**: `password`
- ⚠️ **CHANGE IMMEDIATELY** after first login

## Features Enabled

- ✅ AI-powered product search (requires `ANTHROPIC_API_KEY`)
- ✅ AI content generation
- ✅ Bulk product upload with auto-pricing
- ✅ Trade-In valuation system
- ✅ Community (photos, stories, Q&A) — admin approval required
- ✅ Search analytics dashboard
- ✅ Email notifications for orders & trade-ins
- ✅ WhatsApp checkout integration
- ✅ Flash sales with countdown
