# Project Cleanup Plan - Starter Kit Conversion

## Overview
This document outlines what will be kept and removed to convert this project into a clean Laravel starter kit with authentication, basic home page, and admin routes for general items.

---

## ✅ WHAT TO KEEP

### 1. **Core Framework & Packages**
- ✅ All packages in `composer.json` (Laravel, Jetstream, Inertia, Sanctum, Laratrust, etc.)
- ✅ All packages in `package.json` (Vue 3, Inertia, Tailwind, PrimeVue, etc.)
- ✅ All configuration files in `config/` directory
- ✅ Core Laravel structure (bootstrap, app structure, etc.)

### 2. **Authentication System**
- ✅ Jetstream/Fortify authentication setup
- ✅ Authentication middleware (`Authenticate`, `RedirectIfAuthenticated`, `GeneralAuthentication`, `AdminAuthentication`)
- ✅ Auth routes (login, register, password reset, email verification)
- ✅ Auth Vue components:
  - `resources/js/Guest/Auth/Login.vue`
  - `resources/js/Guest/Auth/Register.vue`
  - `resources/js/Guest/Auth/ForgotPassword.vue`
  - `resources/js/Guest/Auth/ResetPassword.vue`
  - `resources/js/Guest/Auth/VerifyEmail.vue`
  - `resources/js/Guest/Auth/ConfirmPassword.vue`
- ✅ Auth-related Actions (`app/Actions/Fortify/`, `app/Actions/Jetstream/`)
- ✅ Auth providers (`FortifyServiceProvider`, `JetstreamServiceProvider`)

### 3. **User Management & Roles (System Routes)**
- ✅ User model (`app/Models/User.php`)
- ✅ Role & Permission models (`app/Models/Role.php`, `app/Models/Permission.php`)
- ✅ System controllers:
  - `app/Http/Controllers/System/UsersContoller.php`
  - `app/Http/Controllers/Admin/RoleController.php`
  - `app/Http/Controllers/Admin/PermissionController.php`
- ✅ System routes in `routes/system.php`:
  - `/system/user` - User management
  - `/system/roles` - Role management
  - `/system/permissions` - Permission management
  - `/system/company-information` - Company settings
  - `/system/social-media` - Social media links
  - `/system/attachment` - File attachments
- ✅ System Vue pages:
  - `resources/js/System/Pages/Users/`
  - `resources/js/Admin/Pages/Role/`
  - `resources/js/Admin/Pages/Permission/`
  - `resources/js/System/Pages/CompanyInformation/`
  - `resources/js/System/Pages/SocialMedia/`

### 4. **Basic Home Page**
- ✅ Home route (`/`) - simplified version
- ✅ `app/Http/Controllers/GeneralController.php` - keep basic structure, remove domain-specific methods
- ✅ `resources/js/Guest/Pages/Home.vue` - simplified starter version
- ✅ Guest layout components (`resources/js/Guest/Layouts/`)

### 5. **Admin Dashboard Structure**
- ✅ Admin dashboard route (`/admin/dashboard`)
- ✅ `app/Http/Controllers/Admin/GeneralController.php` - keep dashboard method, remove domain-specific stats
- ✅ `resources/js/Admin/Pages/Dashboard.vue` - basic dashboard
- ✅ Admin layout (`resources/js/Admin/Layouts/AppLayout.vue`)
- ✅ Admin sidebar/navbar components (`resources/js/Admin/Partials/`)

### 6. **Core Components & Utilities**
- ✅ All reusable components in `resources/js/Components/`:
  - Form components (Input, Select, Textarea, etc.)
  - UI components (Button, Card, Modal, etc.)
  - Table components
  - Chart components (for future use)
- ✅ Composables (`resources/js/Composables/`)
- ✅ Utils (`resources/js/Utils/`)
- ✅ Stores (`resources/js/stores/`)

### 7. **Core Migrations**
- ✅ `2014_10_12_000000_create_users_table.php`
- ✅ `2014_10_12_100000_create_password_reset_tokens_table.php`
- ✅ `2014_10_12_200000_add_two_factor_columns_to_users_table.php`
- ✅ `2019_08_19_000000_create_failed_jobs_table.php`
- ✅ `2019_12_14_000001_create_personal_access_tokens_table.php`
- ✅ `2024_10_12_212258_create_sessions_table.php`
- ✅ `2024_10_19_014312_laratrust_setup_tables.php` (roles & permissions)
- ✅ `2024_10_19_010330_create_company_information_table.php`
- ✅ `2024_10_19_011759_create_attachments_table.php`
- ✅ `2024_10_19_011952_create_social_media_table.php`

### 7a. **Additional Migrations to Retain** (For Models Above)
- ✅ `2024_10_20_045712_create_events_table.php`
- ✅ `2024_10_21_003537_alter_content_in_events_table.php`
- ✅ `2024_10_22_033512_add_field_in_events_table.php`
- ✅ `2025_06_06_112912_add_type_speakers_attendees_price_to_events_table.php`
- ✅ `2024_10_21_004527_create_galleries_table.php`
- ✅ `2025_06_04_141311_add_category_to_gallery_table.php`
- ✅ `2025_09_05_175200_create_album_images_table.php`
- ✅ `2024_10_19_013100_create_feedback_table.php`
- ✅ `2024_10_19_013220_create_inquiries_table.php`
- ✅ `2025_06_11_032617_add_phone_number_and_subject_to_inquiries_table.php`
- ✅ `2024_10_20_045730_create_news_table.php`
- ✅ `2024_10_21_003449_alter_content_in_news_table.php`
- ✅ `2024_10_22_032236_add_field_in_news_table.php`
- ✅ `2024_03_19_000001_create_partners_table.php`
- ✅ `2024_10_19_012833_create_testimonials_table.php`
- ✅ Any migrations related to Reports, Faq, Service models

### 8. **Core Models**
- ✅ `app/Models/User.php`
- ✅ `app/Models/Role.php`
- ✅ `app/Models/Permission.php`
- ✅ `app/Models/System/AdminUser.php`
- ✅ `app/Models/System/Attachment.php`
- ✅ `app/Models/System/CompanyInformation.php`
- ✅ `app/Models/System/SocialMedia.php`
- ✅ `app/Models/System/Service.php`

### 8a. **Additional Models to Retain** (User Requested)
- ✅ `app/Models/Faq.php`
- ✅ `app/Models/Event.php`
- ✅ `app/Models/AlbumImage.php`
- ✅ `app/Models/Gallery.php`
- ✅ `app/Models/Feedback.php`
- ✅ `app/Models/Inquiry.php`
- ✅ `app/Models/News.php`
- ✅ `app/Models/Partner.php`
- ✅ `app/Models/Report.php`
- ✅ `app/Models/Testimonial.php`

### 9. **Core Traits & Services**
- ✅ **ALL Traits** in `app/Traits/` directory:
  - `app/Traits/Admin/LayoutTrait.php`
  - `app/Traits/Admin/UploadFileTrait.php`
  - `app/Traits/Admin/ChartsTrait.php`
  - `app/Traits/Admin/ColumnsTrait.php`
  - `app/Traits/Admin/SearchTrait.php`
  - `app/Traits/Admin/UuidTrait.php`
  - `app/Traits/NotificationTrait.php`
- ✅ **ALL Services** in `app/Services/` directory:
  - `app/Services/UserRoleService.php`
  - `app/Services/ReportService.php`

### 10. **Middleware & Policies**
- ✅ **ALL middleware** in `app/Http/Middleware/`
- ✅ Core policies (if any generic ones exist)
- ✅ **Settings in `app/Http/Middleware/HandleInertiaRequests.php`** - retain all configuration

### 11. **Core Routes Structure**
- ✅ Basic route structure in `routes/web.php`:
  - Home route (`/`)
  - Dashboard route (`/dashboard`)
  - Admin dashboard (`/admin/dashboard`)
  - Admin routes for retained models (Faq, Event, Gallery, Feedback, Inquiry, News, Partner, Report, Testimonial, Service)
  - System routes (via `routes/system.php`)
- ✅ `routes/api.php` - keep basic structure
- ✅ `routes/system.php` - keep system routes

### 12. **Layout Files & Configuration**
- ✅ **ALL layout files** in Laravel (`resources/views/`)
- ✅ **ALL layout files** in JS (`resources/js/Guest/Layouts/`, `resources/js/Admin/Layouts/`, `resources/js/System/Layouts/`)
- ✅ **ALL providers** in `app/Providers/`
- ✅ **Configuration in `config/app.php`** - retain all settings
- ✅ **Settings in `resources/js/app.js`** - retain all configuration

### 13. **JavaScript Directories**
- ✅ `resources/js/Utils/` - **ALL files**
- ✅ `resources/js/stores/` - **ALL files**
- ✅ `resources/js/Composables/` - **ALL files**
- ✅ `resources/js/Components/` - **ALL files**

---

## ❌ WHAT TO REMOVE

### 1. **Domain-Specific Models** (Remove all except core and user-requested)
- ❌ `AlbumCollection.php` (keep `AlbumImage.php` as requested)
- ❌ `Application.php`, `ScholarshipApplication.php`
- ❌ `Attendance.php`
- ❌ `Award.php`
- ❌ `Branch.php`, `ChurchBranchLeadership.php`, `ChurchLeadership.php`
- ❌ `Course.php`, `CourseType.php`, `TrainingModule.php`
- ❌ `Department.php`, `DepartmentActivity.php`, `DepartmentGoal.php`, `DepartmentHead.php`, `DepartmentRequest.php`
- ❌ `Designation.php`
- ❌ `Fee.php`, `FeeStructure.php`, `PaymentOption.php`, `RegistrationFee.php`
- ❌ `FiscalPeriod.php`, `FiscalYear.php`
- ❌ `ICTContent.php`, `Video.php` (keep `Gallery.php` as requested)
- ❌ `Offering.php`
- ❌ `SchoolLevel.php`, `SpecialNeed.php`
- ❌ `Staff.php`
- ❌ `SuccessStory.php`
- ❌ `Testmony.php` (keep `Testimonial.php` as requested)
- ❌ `app/Models/System/Course.php` (keep `app/Models/System/Service.php` as requested)

### 2. **Domain-Specific Controllers**
- ❌ All controllers in `app/Http/Controllers/Admin/` except:
  - `GeneralController.php` (keep, but clean)
  - `RoleController.php` (keep)
  - `PermissionController.php` (keep)
  - `PasswordController.php` (keep - for password change)
  - **Additional Controllers to Keep (User Requested):**
    - `FaqController.php`
    - `EventController.php`
    - `GalleryController.php`
    - `FeedbackController.php`
    - `InquiriesController.php`
    - `NewsController.php`
    - `PartnerController.php`
    - `ReportController.php`
    - `TestimoniesController.php`
    - `ServicesController.php`
- ✅ `app/Http/Controllers/GeneralController.php` - keep but clean domain-specific methods
- ❌ `app/Http/Controllers/System/CourseController.php`

### 3. **Domain-Specific Routes**
- ❌ All guest routes in `routes/web.php` except:
  - `/` (home)
  - `/dashboard`
  - Routes for retained models (Event, News, Gallery, etc.) if they exist
- ❌ All admin routes in `routes/web.php` except:
  - `/admin/dashboard`
  - `/admin/settings/change-password`
  - **Admin routes for retained models:**
    - `/admin/faq` routes
    - `/admin/event` routes
    - `/admin/gallery` routes
    - `/admin/feedback` routes
    - `/admin/inquiry` routes
    - `/admin/news` routes
    - `/admin/partner` routes
    - `/admin/report` routes
    - `/admin/testimony` routes
    - `/admin/course` routes (for Service)
  - System routes (handled in `system.php`)

### 4. **Domain-Specific Vue Pages**
- ❌ All pages in `resources/js/Admin/Pages/` except:
  - `Dashboard.vue`
  - `Role/` directory
  - `Permission/` directory
  - `Settings/` directory (if exists)
  - **Vue pages for retained models:**
    - `Faqs/` directory
    - `Events/` directory
    - `Gallery/` directory
    - `Feedback/` directory
    - `Inquiry/` directory
    - `News/` directory
    - `Partner/` directory
    - `Reports/` directory (if exists)
    - `Testimony/` directory
    - `Service/` directory
- ❌ All pages in `resources/js/Guest/Pages/` except:
  - `Home.vue` (simplified)
  - Pages for retained models if they exist
- ❌ All domain-specific guest partials (except those used by retained models)

### 5. **Domain-Specific Migrations**
- ❌ All migrations except:
  - Core ones listed in "KEEP" section (section 7)
  - Migrations for retained models listed in section 7a

### 6. **Domain-Specific Mail/Notifications**
- ❌ `app/Mail/DepartmentRequestNotification.php`
- ❌ `app/Mail/DepartmentRequestStatusNotification.php`
- ❌ `app/Notifications/FiscalPeriodEndingNotification.php`
- ✅ Keep: `app/Notifications/ResetPasswordNotification.php`

### 7. **Domain-Specific Observers**
- ❌ `app/Observers/FiscalPeriodObserver.php`
- ❌ `app/Observers/FiscalYearObserver.php`

### 8. **Domain-Specific Policies**
- ❌ `app/Policies/DepartmentHeadPolicy.php`
- ❌ `app/Policies/DesignationPolicy.php`
- ❌ `app/Policies/FiscalPeriodPolicy.php`
- ❌ `app/Policies/FiscalYearPolicy.php`

### 9. **Domain-Specific Services**
- ❌ `app/Services/ReportService.php` (if domain-specific)

### 10. **Domain-Specific Documentation**
- ❌ `CORS_Issue_Resolution_Documentation.html`
- ❌ `CORS_Issue_Resolution_Documentation.md`
- ❌ `DEPLOYMENT.md`
- ❌ `deployment_diagram.mmd`
- ❌ `Proposal_Milestone_Reproductive_Healthcare_Wenla.pdf`
- ❌ `WHATSAPP_FEATURE.md`
- ❌ `deploy.sh`

### 11. **Database Files**
- ❌ `database/novus (3).sql`

### 12. **Storage Files** (User-generated content)
- ❌ All files in `storage/app/` (except structure)
- ❌ All files in `public/storage/`
- ❌ All files in `public/thumbnails/`
- ❌ Keep structure but remove content

---

## 📋 CLEANUP STEPS SUMMARY

1. **Routes Cleanup**
   - Simplify `routes/web.php` to basic routes only
   - Keep `routes/system.php` for system management
   - Keep `routes/api.php` basic structure

2. **Controllers Cleanup**
   - Remove all domain-specific Admin controllers
   - Clean `GeneralController.php` to basic methods
   - Clean `Admin/GeneralController.php` to basic dashboard

3. **Models Cleanup**
   - Remove all domain-specific models
   - Keep only core models (User, Role, Permission, System models)

4. **Migrations Cleanup**
   - Remove all domain-specific migrations
   - Keep only core authentication and system migrations

5. **Vue Components Cleanup**
   - Remove all domain-specific pages
   - Simplify Home.vue to a basic starter template
   - Keep all reusable components

6. **Remove Domain-Specific Files**
   - Mail classes, Observers, Policies, Services
   - Documentation files
   - Database dumps

7. **Update README**
   - Create a clean README for the starter kit

---

## 🎯 FINAL STRUCTURE

After cleanup, the starter kit will have:

- ✅ Authentication (Login, Register, Password Reset, Email Verification)
- ✅ Basic Home Page
- ✅ Admin Dashboard (basic structure)
- ✅ User Management (CRUD)
- ✅ Role & Permission Management (CRUD)
- ✅ Company Information Management
- ✅ Social Media Links Management
- ✅ File Attachment System
- ✅ **Content Management Modules:**
  - FAQ Management (CRUD)
  - Event Management (CRUD)
  - Gallery Management (CRUD) with Album Images
  - Feedback Management (CRUD)
  - Inquiry Management (CRUD)
  - News Management (CRUD)
  - Partner Management (CRUD)
  - Report Management (CRUD)
  - Testimonial Management (CRUD)
  - Service Management (CRUD)
- ✅ All reusable Vue components
- ✅ All Traits and Services
- ✅ All Layout files (Laravel & JS)
- ✅ All Providers and Middleware
- ✅ All packages and dependencies
- ✅ Clean, ready-to-customize structure

---

## ⚠️ NOTES

- All packages remain unchanged
- Configuration files remain unchanged
- Core middleware and authentication remain unchanged
- The structure will be ready for you to add your own domain-specific features
- You'll need to update the Home page content to match your new project
- You'll need to customize the admin dashboard for your needs

