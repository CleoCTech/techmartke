# Laravel Starter Kit Documentation

## 🔐 Quick Login Credentials

> ⚠️ **SECURITY WARNING:** Change these default passwords immediately after first login!

### Administrator Access
- **URL:** `http://localhost:8000/login`
- **Email:** `admin@example.com`
- **Password:** `password`
- **Access:** Full admin panel access

### Test User Access
- **URL:** `http://localhost:8000/login`
- **Email:** `user@example.com`
- **Password:** `password`
- **Access:** Limited user dashboard

---

## Overview

This is a clean Laravel starter kit built with:
- **Laravel 10** - PHP Framework
- **Vue 3** - Frontend Framework
- **Inertia.js** - Modern monolith approach
- **Jetstream** - Authentication scaffolding
- **Laratrust** - Roles and Permissions
- **Tailwind CSS** - Utility-first CSS framework
- **PrimeVue** - Vue UI Component Library

---

## 🚀 Quick Start

### Prerequisites

- PHP 8.1 or higher
- Composer
- Node.js and NPM
- MySQL/MariaDB
- Git

### Installation

1. **Clone the repository**
   ```bash
   git clone <repository-url>
   cd MRH
   ```

2. **Install PHP dependencies**
   ```bash
   composer install
   ```

3. **Install Node dependencies**
   ```bash
   npm install
   ```

4. **Environment Setup**
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```

5. **Configure Database**
   Update your `.env` file with your database credentials:
   ```env
   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=your_database_name
   DB_USERNAME=your_username
   DB_PASSWORD=your_password
   ```

6. **Run Migrations and Seeders**
   ```bash
   php artisan migrate --seed
   ```

7. **Create Storage Link**
   ```bash
   php artisan storage:link
   ```

8. **Build Assets**
   ```bash
   npm run dev
   # or for production
   npm run build
   ```

9. **Start Development Server**
   ```bash
   php artisan serve
   ```

   The application will be available at `http://localhost:8000`

---

## 👤 Default User Accounts

> ⚠️ **IMPORTANT:** These are default accounts created during seeding. **Change these passwords immediately** before deploying to production!

### 🔑 Administrator Account

**Login Credentials:**
- **Email:** `admin@example.com`
- **Password:** `password`
- **Role:** Administrator
- **Access Level:** Full system access with all permissions

**Access URL:** `/login` or `/admin/dashboard`

**Capabilities:**
- ✅ Full access to admin dashboard
- ✅ User management (Create, Read, Update, Delete)
- ✅ Role and Permission management
- ✅ All content management modules (FAQ, Events, News, Gallery, etc.)
- ✅ System settings and configuration
- ✅ Company information management
- ✅ Social media links management
- ✅ File attachment management

**To Login:**
1. Navigate to `/login`
2. Enter email: `admin@example.com`
3. Enter password: `password`
4. Click "Login"
5. You will be redirected to `/admin/dashboard`

---

### 👤 Test User Account

**Login Credentials:**
- **Email:** `user@example.com`
- **Password:** `password`
- **Role:** Regular User
- **Access Level:** Limited access

**Access URL:** `/login` or `/dashboard`

**Capabilities:**
- ✅ Basic dashboard access
- ✅ Limited content viewing
- ❌ No admin panel access
- ❌ No content management access

**To Login:**
1. Navigate to `/login`
2. Enter email: `user@example.com`
3. Enter password: `password`
4. Click "Login"
5. You will be redirected to `/dashboard`

---

### 🔐 Changing Default Passwords

**For Administrator:**
```bash
php artisan tinker
$user = App\Models\User::where('email', 'admin@example.com')->first();
$user->password = Hash::make('your_new_password');
$user->save();
```

**For Test User:**
```bash
php artisan tinker
$user = App\Models\User::where('email', 'user@example.com')->first();
$user->password = Hash::make('your_new_password');
$user->save();
```

Or use the admin panel at `/admin/settings/change-password` after logging in.

---

## 🔐 Authentication

The application uses **Laravel Jetstream** with **Fortify** for authentication.

### Available Auth Routes

- `/login` - User login
- `/register` - User registration
- `/forgot-password` - Password reset request
- `/reset-password/{token}` - Password reset
- `/email/verify` - Email verification
- `/dashboard` - User dashboard (requires authentication)

### Admin Routes

- `/admin/dashboard` - Admin dashboard
- `/admin/settings/change-password` - Change password

---

## 📋 Content Management Modules

The starter kit includes the following content management modules:

### 1. FAQ Management
- **Route:** `/admin/faq`
- **Model:** `App\Models\Faq`
- **Features:** Create, read, update, delete FAQs
- **Default Data:** 4 sample FAQs

### 2. Event Management
- **Route:** `/admin/event`
- **Model:** `App\Models\Event`
- **Features:** Full CRUD, calendar view, event scheduling
- **Default Data:** 2 sample events

### 3. Gallery Management
- **Route:** `/admin/gallery`
- **Model:** `App\Models\Gallery`
- **Features:** Image galleries with categories
- **Default Data:** 2 sample galleries

### 4. Feedback Management
- **Route:** `/admin/feedback`
- **Model:** `App\Models\Feedback`
- **Features:** Collect and manage user feedback

### 5. Inquiry Management
- **Route:** `/admin/inquiry`
- **Model:** `App\Models\Inquiry`
- **Features:** Manage customer inquiries from contact forms

### 6. News Management
- **Route:** `/admin/news`
- **Model:** `App\Models\News`
- **Features:** News articles with rich content
- **Default Data:** 2 sample news articles

### 7. Partner Management
- **Route:** `/admin/partner`
- **Model:** `App\Models\Partner`
- **Features:** Manage partner organizations
- **Default Data:** 2 sample partners

### 8. Report Management
- **Route:** `/admin/report`
- **Model:** `App\Models\Report`
- **Features:** Generate and manage reports

### 9. Testimonial Management
- **Route:** `/admin/testimony`
- **Model:** `App\Models\Testimonial`
- **Features:** Customer testimonials with ratings
- **Default Data:** 3 sample testimonials

### 10. Service Management
- **Route:** `/admin/course` (Note: Uses Service model)
- **Model:** `App\Models\System\Service`
- **Features:** Service offerings management

---

## 🗄️ Database Structure

### Core Tables

- `users` - User accounts
- `roles` - User roles
- `permissions` - System permissions
- `role_user` - User-role relationships
- `permission_user` - User-permission relationships
- `sessions` - User sessions
- `password_reset_tokens` - Password reset tokens
- `personal_access_tokens` - API tokens

### System Tables

- `company_information` - Company/organization details
- `social_media` - Social media links
- `attachments` - File attachments

### Content Tables

- `faqs` - Frequently asked questions
- `events` - Events calendar
- `galleries` - Image galleries
- `album_collections` - Album collections
- `album_images` - Album images
- `feedback` - User feedback
- `inquiries` - Customer inquiries
- `news` - News articles
- `partners` - Partner organizations
- `reports` - System reports
- `services` - Service offerings
- `testimonials` - Customer testimonials

---

## 🏢 Company Information

The default company information is configured with **Kenya-specific details**:

### Default Company Details

- **Company Name:** Your Company Name
- **Short Name:** YCN
- **Email:** info@example.co.ke
- **Phone Numbers:** +254 700 000 000
- **Location:** Nairobi, Kenya
- **Address:** Nairobi, Kenya
- **Total Members:** 1000+
- **Branches:** 5

### Mission & Vision

- **Mission:** To deliver exceptional value and service excellence to our clients across Kenya and beyond.
- **Vision:** To be the premier service provider in Kenya, recognized for innovation, integrity, and excellence.
- **Core Values:** Integrity, Excellence, Innovation, Customer Focus, Community Impact
- **Motto:** Excellence in Service

### Updating Company Information

You can update this information in two ways:

**Option 1: Through Admin Panel**
1. Login as administrator
2. Navigate to `/system/company-information`
3. Edit and save the information

**Option 2: Through Seeder**
```bash
php artisan db:seed --class=CompanyInformationSeeder --force
```

**Option 3: Direct Database Update**
```bash
php artisan tinker
$company = App\Models\System\CompanyInformation::first();
$company->company_name = 'Your New Company Name';
$company->emails = 'info@yourcompany.co.ke';
$company->phone_numbers = '+254 700 000 000';
$company->location = 'Nairobi, Kenya';
$company->save();
```

---

## 🔑 Roles and Permissions

### Default Roles

1. **Administrator**
   - Full system access
   - All permissions assigned

2. **Super Admin**
   - Full system access
   - All permissions assigned

3. **Editor**
   - Content creation and editing
   - Limited permissions

### Default Permissions

- `manageusers` - Manage Users
- `manageroles` - Manage Roles
- `manageevents` - Manage Events
- `managenews` - Manage News
- `managegallery` - Manage Gallery
- `managefaq` - Manage FAQ
- `managepartners` - Manage Partners
- `managetestimonials` - Manage Testimonials
- `manageinquiries` - Manage Inquiries
- `managefeedback` - Manage Feedback

---

## 📁 Project Structure

```
app/
├── Http/
│   ├── Controllers/
│   │   ├── Admin/          # Admin controllers
│   │   ├── System/         # System controllers
│   │   └── GeneralController.php
│   └── Middleware/         # Custom middleware
├── Models/                 # Eloquent models
│   └── System/            # System models
├── Services/              # Business logic services
├── Traits/                # Reusable traits
└── Providers/             # Service providers

resources/
├── js/
│   ├── Admin/             # Admin pages
│   ├── Guest/             # Guest/public pages
│   ├── Components/        # Reusable Vue components
│   ├── Composables/       # Vue composables
│   ├── Utils/             # Utility functions
│   └── stores/            # Pinia stores
└── views/                 # Blade templates

database/
├── migrations/            # Database migrations
└── seeders/              # Database seeders
```

---

## 🛠️ Available Commands

### Database Commands

```bash
# Run migrations
php artisan migrate

# Rollback migrations
php artisan migrate:rollback

# Run seeders
php artisan db:seed

# Run specific seeder
php artisan db:seed --class=UserSeeder

# Fresh migration with seeding
php artisan migrate:fresh --seed
```

### Asset Commands

```bash
# Development build with hot reload
npm run dev

# Production build
npm run build

# Watch for changes
npm run watch
```

### Cache Commands

```bash
# Clear all caches
php artisan optimize:clear

# Cache configuration
php artisan config:cache

# Cache routes
php artisan route:cache

# Cache views
php artisan view:cache
```

---

## 🔧 Configuration

### Key Configuration Files

- `.env` - Environment variables
- `config/app.php` - Application configuration
- `config/auth.php` - Authentication configuration
- `config/jetstream.php` - Jetstream configuration
- `config/laratrust.php` - Laratrust configuration

### Important Environment Variables

```env
APP_NAME="Your App Name"
APP_URL=http://localhost:8000
APP_ENV=local
APP_DEBUG=true

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=your_database
DB_USERNAME=your_username
DB_PASSWORD=your_password

COMPANY_NAME="Your Company Name"
COMPANY_SHORT_NAME="YCN"
```

---

## 📝 Default Seed Data

After running `php artisan migrate --seed`, the following default data is created:

### 👥 Users (2 accounts)
- **Administrator:** `admin@example.com` / `password`
- **Test User:** `user@example.com` / `password`

### 🎭 Roles (3 roles)
- **Administrator** - Full system access
- **Super Admin** - Full system access
- **Editor** - Content creation and editing

### 🔑 Permissions (10 permissions)
- `manageusers` - Manage Users
- `manageroles` - Manage Roles
- `manageevents` - Manage Events
- `managenews` - Manage News
- `managegallery` - Manage Gallery
- `managefaq` - Manage FAQ
- `managepartners` - Manage Partners
- `managetestimonials` - Manage Testimonials
- `manageinquiries` - Manage Inquiries
- `managefeedback` - Manage Feedback

### 📄 Content Samples
- **4 FAQs** - Sample frequently asked questions
- **2 Events** - Sample upcoming events
- **2 News Articles** - Sample news posts
- **2 Galleries** - Sample image galleries
- **2 Partners** - Sample partner organizations
- **3 Testimonials** - Sample customer testimonials

### 🏢 System Data
- **Company Information** - Kenya-based company details
  - Location: Nairobi, Kenya
  - Email: info@example.co.ke
  - Phone: +254 700 000 000
- **5 Social Media Links** - Facebook, Twitter, Instagram, LinkedIn, YouTube

---

## 🚨 Security Notes

⚠️ **IMPORTANT:** Before deploying to production:

1. **Change default passwords** - Update all default user passwords
2. **Update company information** - Replace placeholder data with real information
3. **Set `APP_DEBUG=false`** in production
4. **Use strong database passwords**
5. **Enable HTTPS** in production
6. **Review and configure CORS** settings
7. **Set up proper file permissions** for storage directories

---

## 📚 Additional Resources

### Laravel Documentation
- [Laravel 10.x Docs](https://laravel.com/docs/10.x)
- [Jetstream Docs](https://jetstream.laravel.com)
- [Inertia.js Docs](https://inertiajs.com)

### Vue 3 Documentation
- [Vue 3 Docs](https://vuejs.org)
- [PrimeVue Docs](https://primevue.org)

### Package Documentation
- [Laratrust](https://laratrust.santigarcor.me)
- [Tailwind CSS](https://tailwindcss.com)

---

## 🐛 Troubleshooting

### Migration Issues

If you encounter migration errors:

```bash
# Reset database
php artisan migrate:fresh --seed
```

### Permission Issues

If you have file permission issues:

```bash
# Fix storage permissions
chmod -R 775 storage bootstrap/cache
```

### Asset Build Issues

If assets don't build:

```bash
# Clear node modules and reinstall
rm -rf node_modules package-lock.json
npm install
npm run dev
```

---

## 📞 Support

For issues or questions:
- Check the Laravel documentation
- Review the codebase comments
- Check GitHub issues (if applicable)

---

## 📄 License

[Your License Here]

---

**Last Updated:** December 2025

