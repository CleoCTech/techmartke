# VILT Starter Kit

<div align="center">

![VILT Starter Kit](https://img.shields.io/badge/VILT-Starter%20Kit-indigo?style=for-the-badge)
![Laravel](https://img.shields.io/badge/Laravel-10.x-red?style=for-the-badge&logo=laravel)
![Vue.js](https://img.shields.io/badge/Vue.js-3.x-green?style=for-the-badge&logo=vue.js)
![Inertia.js](https://img.shields.io/badge/Inertia.js-1.x-purple?style=for-the-badge)
![Tailwind CSS](https://img.shields.io/badge/Tailwind%20CSS-3.x-cyan?style=for-the-badge&logo=tailwind-css)

**A powerful, modern starter kit combining Vue.js, Inertia.js, Laravel, and Tailwind CSS to accelerate your development workflow.**

[Features](#features) • [Installation](#installation) • [Documentation](#documentation) • [Support](#support)

</div>

---

## 🚀 Overview

VILT Starter Kit is a production-ready starter kit that combines the best of modern web development technologies. Built with **Laravel 10**, **Vue 3**, **Inertia.js**, and **Tailwind CSS**, it provides everything you need to build scalable, maintainable web applications without the hassle of setting up infrastructure from scratch.

### Why Choose VILT Starter Kit?

- ⚡ **Save Time** - Skip weeks of setup and boilerplate code. Start building features from day one.
- 🛡️ **Production Ready** - Built with security, performance, and scalability in mind. Deploy with confidence.
- 🎨 **Beautiful UI** - Modern, responsive design with dark mode support. Impress your users from the start.
- 📚 **Well Documented** - Comprehensive documentation to help you understand and extend the codebase.
- 🔧 **Easy to Customize** - Clean code architecture makes it easy to modify and extend according to your needs.
- 🚀 **Fast Performance** - Optimized for speed with modern best practices and efficient code patterns.

---

## 🛠️ Technology Stack

VILT Starter Kit is built with industry-leading tools and frameworks:

| Technology | Version | Purpose |
|------------|---------|---------|
| **Laravel** | 10.x | Backend framework |
| **Vue.js** | 3.x | Frontend framework (Composition API) |
| **Inertia.js** | 1.x | SPA-like experience without API complexity |
| **Tailwind CSS** | 3.x | Utility-first CSS framework |
| **Laravel Jetstream** | Latest | Authentication scaffolding |
| **Laratrust** | Latest | Roles & permissions management |

---

## ✨ Features

### 🔐 Complete Authentication
Built-in authentication system powered by Laravel Jetstream with:
- ✅ Login & Registration
- ✅ Email Verification
- ✅ Password Reset
- ✅ Role-Based Access Control

### 📊 Ready Dashboard
Beautiful, responsive admin dashboard with:
- ✅ Analytics Dashboard
- ✅ User Management
- ✅ Responsive Design
- ✅ Dark Mode Support

### 🧩 Reusable Components
Extensive library of pre-built Vue components:
- ✅ Form Components
- ✅ Data Tables
- ✅ Charts & Graphs
- ✅ UI Elements

### 🏗️ Adaptable Patterns
Clean, maintainable code architecture:
- ✅ MVC Architecture
- ✅ Service Layer Pattern
- ✅ Repository Pattern
- ✅ Component-Based UI

---

## 📦 Installation

### Prerequisites

- PHP >= 8.1
- Composer
- Node.js >= 16.x and npm
- MySQL/PostgreSQL/SQLite
- Git

### Step 1: Clone the Repository

```bash
git clone https://github.com/CleoCTech/VILT-STARTER-KIT.git
cd VILT-STARTER-KIT
```

### Step 2: Install Dependencies

```bash
# Install PHP dependencies
composer install

# Install Node.js dependencies
npm install
```

### Step 3: Environment Configuration

```bash
# Copy environment file
cp .env.example .env

# Generate application key
php artisan key:generate
```

Configure your database settings in the `.env` file:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=your_database_name
DB_USERNAME=your_database_user
DB_PASSWORD=your_database_password
```

### Step 4: Database Setup

```bash
# Run migrations
php artisan migrate
```

### Step 5: ⚠️ Seed Database (IMPORTANT)

**⚠️ Important: Seed the database before accessing the dashboard!**

The database must be seeded to create the default admin user and initial data:

```bash
php artisan db:seed
```

This will create:
- Default admin user
- Essential roles and permissions
- Sample data for testing

### Step 6: Build Frontend Assets

```bash
# For development (with hot reload)
npm run dev

# For production
npm run build
```

### Step 7: Start Development Server

```bash
# Start Laravel development server
php artisan serve

# The application will be available at http://localhost:8000
```

---

## 🔑 Default Credentials

After seeding the database, you can log in to the dashboard using these default credentials:

| Field | Value |
|-------|-------|
| **Email** | `admin@example.com` |
| **Password** | `password` |

> ⚠️ **Security Note:** Please change the default password after your first login for security purposes.

---

## 🏗️ Architecture Overview

VILT Starter Kit uses a modern stack combining:

- **Laravel** as the backend framework - handles HTTP requests, business logic, and database operations
- **Vue 3** with Composition API for the frontend - provides reactive, component-based UI
- **Inertia.js** for seamless SPA-like experience - bridges Laravel and Vue without the complexity of a separate API
- **Tailwind CSS** for rapid UI development - utility-first CSS framework for quick styling

### Development Workflow

The starter kit follows Laravel conventions and best practices:

- **Controllers** handle HTTP requests
- **Services** contain business logic
- **Models** represent database entities
- **Vue Components** handle the user interface
- **Inertia.js** bridges the gap, allowing you to use Laravel routes and controllers while enjoying a modern SPA experience

### Key Features Explained

1. **Authentication:** Complete authentication system with Laravel Jetstream including login, registration, email verification, and password reset.

2. **Role-Based Access Control:** Built-in roles and permissions system using Laratrust for managing user access levels.

3. **Admin Dashboard:** Ready-to-use admin dashboard with analytics, user management, and customizable widgets.

4. **Reusable Components:** Extensive library of Vue components for forms, tables, modals, charts, and more.

5. **Adaptable Patterns:** Clean code architecture following MVC, Service Layer, and Repository patterns for easy customization.

---

## 📁 Project Structure

```
VILT-STARTER-KIT/
├── app/
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── Admin/          # Admin controllers
│   │   │   └── GeneralController.php
│   │   └── Middleware/
│   ├── Models/                  # Eloquent models
│   └── Traits/                  # Reusable traits
├── database/
│   ├── migrations/              # Database migrations
│   └── seeders/                 # Database seeders
├── resources/
│   ├── js/
│   │   ├── Admin/              # Admin Vue components
│   │   ├── Components/         # Reusable Vue components
│   │   ├── Guest/              # Guest/public components
│   │   └── Pages/              # Page components
│   └── views/                   # Blade templates
├── routes/
│   ├── web.php                  # Web routes
│   └── system.php               # System routes
└── public/                      # Public assets
```

---

## 🧪 Development

### Running Tests

```bash
php artisan test
```

### Code Style

The project follows PSR-12 coding standards and Vue.js style guide.

### Building Assets

```bash
# Development build with hot reload
npm run dev

# Production build
npm run build
```

---

## 📚 Additional Resources

- [Laravel Documentation](https://laravel.com/docs)
- [Vue.js Documentation](https://vuejs.org/)
- [Inertia.js Documentation](https://inertiajs.com/)
- [Tailwind CSS Documentation](https://tailwindcss.com/docs)

---

## 🤝 Support

Need help? We're here for you!

- 📧 **Email:** [cleoctech@gmail.com](mailto:cleoctech@gmail.com)
- 📱 **Phone:** [+254 727 057 310](tel:+254727057310)
- 🐦 **X (Twitter):** [@cleo_hacker](https://x.com/cleo_hacker)
- 🌐 **Website:** [comwengas.com](https://comwengas.com)
- 💻 **GitHub:** [@CleoCTech](https://github.com/CleoCTech)

---

## 📄 License

This project is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).

---

## 🙏 Credits

**Powered by [Wenla Systems](https://wenlasystems.com/)**

VILT Starter Kit is developed and maintained by [CleoCTech](https://github.com/CleoCTech).

---

## 🚀 Quick Start Summary

```bash
# 1. Clone repository
git clone https://github.com/CleoCTech/VILT-STARTER-KIT.git
cd VILT-STARTER-KIT

# 2. Install dependencies
composer install && npm install

# 3. Setup environment
cp .env.example .env
php artisan key:generate

# 4. Configure database in .env file

# 5. Run migrations and seed
php artisan migrate
php artisan db:seed  # ⚠️ IMPORTANT!

# 6. Build assets
npm run build

# 7. Start server
php artisan serve

# 8. Login with:
# Email: admin@example.com
# Password: password
```

---

<div align="center">

**Built with ❤️ by [CleoCTech](https://github.com/CleoCTech)**

⭐ Star this repo if you find it helpful!

</div>
