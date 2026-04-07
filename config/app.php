<?php

use Illuminate\Support\Facades\Facade;

return [

    /*
    |--------------------------------------------------------------------------
    | Custom Variables
    |--------------------------------------------------------------------------
    |*/

    'reportsPath' => 'storage/reports/',
    'storagePaths' => [
        'users' => [
            'profPics' => ["storePath" => "users/prof_pics/","readPath" => "/storage/users/prof_pics/",'disk' => "public"],
        ],
        'industries' => [
            'cover_images' => ["storePath" => "industries/cover_images/","readPath" => "/storage/industries/cover_images/",'disk' => "public"],
        ],
        'solutions' => [
            'cover_images' => ["storePath" => "solutions/cover_images/","readPath" => "/storage/solutions/cover_images/",'disk' => "public"],
        ],
        'posts' => [
            'posts' => ["storePath" => "posts/cover_images/","readPath" => "/storage/posts/cover_images/",'disk' => "public"],
        ],
        'steps' => [
            'cover_images' => ["storePath" => "steps/cover_images/","readPath" => "/storage/steps/cover_images/",'disk' => "public"],
        ],
        'clients' => [
            'logos' => ["storePath" => "clients/logos/","readPath" => "/storage/clients/logos/",'disk' => "public"],
        ],
        'sectionimages' => [
            'cover_images' => ["storePath" => "sectionimages/cover_images/","readPath" => "/storage/sectionimages/cover_images/",'disk' => "public"],
        ],
        'event' => [
            'cover_images' => ["storePath" => "event/cover_images/","readPath" => "/storage/event/cover_images/",'disk' => "public"],
        ],
        'news' => [
            'cover_images' => ["storePath" => "news/cover_images/","readPath" => "/storage/news/cover_images/",'disk' => "public"],
        ],
        'staff' => [
            'cover_images' => ["storePath" => "staff/cover_images/","readPath" => "/storage/staff/cover_images/",'disk' => "public"],
        ],
        'course' => [
            'cover_images' => ["storePath" => "course/cover_images/","readPath" => "/storage/course/cover_images/",'disk' => "public"],
        ],
        'service' => [
            'cover_images' => ["storePath" => "service/cover_images/","readPath" => "/storage/service/cover_images/",'disk' => "public"],
        ],
        'gallery' => [
            'cover_images' => ["storePath" => "gallery/cover_images/","readPath" => "/storage/gallery/cover_images/",'disk' => "public"],
        ],
        'success_stories' => [
            'cover_images' => ["storePath" => "success_stories/cover_images/","readPath" => "/storage/success_stories/cover_images/",'disk' => "public"],
        ],
        'thumbnails' => [
            'cover_images' => ["storePath" => "thumbnails/cover_images/","readPath" => "/storage/thumbnails/cover_images/",'disk' => "public"],
        ],
        'companyInfo' => [
            'images' => ["storePath" => "companyInfo/images/","readPath" => "/storage/companyInfo/images/",'disk' => "public"],
            'manager' => ["storePath" => "companyInfo/manager/","readPath" => "/storage/companyInfo/manager/",'disk' => "public"],
        ],
        'adminAttachments' => [
            'attachments' => ["storePath" => "attachments/admin/","readPath" => "/storage/attachments/admin/",'disk' => "public"],
        ],
        'applications' => [
            'attachments' => ["storePath" => "attachments/applications/","readPath" => "/storage/attachments/applications/",'disk' => "public"],
        ],
        'scholarshipApplications' => [
            'attachments' => ["storePath" => "attachments/scholarship_applications/","readPath" => "/storage/attachments/scholarship_applications/",'disk' => "public"],
        ],
        'ictFiles' => [
            'cover_images' => ["storePath" => "ictFiles/cover_images/","readPath" => "/storage/cover_images/",'disk' => "public"],
            'videos' => ["storePath" => "ictFiles/videos/","readPath" => "/storage/videos/",'disk' => "public"],
        ],
        'videos' => [
            'cover_images' => ["storePath" => "videos/cover_images/","readPath" => "/storage/videos/cover_images/",'disk' => "public"],
            'videos ' => ["storePath" => "videos/videos/","readPath" => "/storage/videos/",'disk' => "public"],
        ],
        'courses' => [
            'cover_images' => ["storePath" => "courses/cover_images/","readPath" => "/storage/courses/cover_images/",'disk' => "public"],
        ],
        'album_collections' => [
            'cover_images' => ["storePath" => "album_collections/cover_images/","readPath" => "/storage/album_collections/cover_images/",'disk' => "public"],
            'images' => ["storePath" => "album_collections/images/","readPath" => "/storage/album_collections/images/",'disk' => "public"],
        ],
    ],

    'maxRecsPerPage' => 30,

    'company' => [
        'name' => env('COMPANY_NAME', 'TechMart Kenya'),
        'shortName' => env('COMPANY_SHORT_NAME', 'TechMartKE'),
        'website' => env('APP_URL'),
        'COMPANY_USER' => 'Administrator',
        'COMPANY_EMAIL' => env('COMPANY_EMAIL', 'info@techmart.ke'),
        'COMPANY_PASS' => '12345678',
    ],
    'whatsapp' => [
        'phone' => env('WHATSAPP_PHONE', '254700000000'),
        'default_message' => env('WHATSAPP_DEFAULT_MESSAGE', 'Hi TechMart KE! I\'d like to inquire about your products.'),
        'enabled' => env('WHATSAPP_ENABLED', true),
    ],
    'developer' => [
        'name' => env('DEV_COMPANY_NAME', 'Wenla Systems & Solutions Ltd.'),
        'shortName' => env('DEV_COMPANY_SHORT_NAME', 'Wenla'),
        'website' => env('DEV_URL', 'http://wenlasystems.com/'),
        'pass' => '@dm1n321#',
        'DEV_USER' => 'Wenla',
        'DEV_EMAIL' => 'admin@wenlasystems.com',
        'DEV_PASS' => '@dm1n321#',
        
    ],
    'defaultErrors' => [
        'default' => 'Oops! something went wrong. If the error persists, kindly contact us for help.',
        'permission' => 'Oops! authorization failed.',
        'crud' => [
            'created' => 'Record created successfully',
            'updated' => 'Record updated successfully',
            'deleted' => 'Record Deleted successfully',
        ],
    ],
    'metaDescription' => 'TechMart KE — Kenya\'s trusted marketplace for phones, laptops & accessories. Compare prices with AI, find the best deals on Samsung, iPhone, MacBook & more. New & quality-tested Ex-UK devices. Fast delivery in Nairobi & beyond.',
    'metaKeywords' => 'phones Kenya, buy phone Nairobi, Samsung Kenya, iPhone Kenya, laptops Nairobi, MacBook Kenya, ex-uk phones, refurbished phones Kenya, cheap smartphones Nairobi, phone deals Kenya, TechMart KE, computer shop Nairobi, M-Pesa payment, phone comparison Kenya, budget phones Kenya, gaming laptop Kenya',
    'metaAuthor' => env('COMPANY_NAME', 'TechMart Kenya'),
    'metaPublisher' => env('COMPANY_NAME', 'TechMart Kenya'),
    'metaRobots' => 'index, follow',
    'admin_notification_email' => env('ADMIN_NOTIFICATION_EMAIL', 'dsldev00@gmail.com'),
    'serverType' => env('SERVER_TYPE'),

    /*
    |--------------------------------------------------------------------------
    | Application Name
    |--------------------------------------------------------------------------
    |
    | This value is the name of your application. This value is used when the
    | framework needs to place the application's name in a notification or
    | any other location as required by the application or its packages.
    |
    */

    'name' => env('APP_NAME', 'Laravel'),

    /*
    |--------------------------------------------------------------------------
    | Application Environment
    |--------------------------------------------------------------------------
    |
    | This value determines the "environment" your application is currently
    | running in. This may determine how you prefer to configure various
    | services the application utilizes. Set this in your ".env" file.
    |
    */

    'env' => env('APP_ENV', 'production'),

    /*
    |--------------------------------------------------------------------------
    | Application Debug Mode
    |--------------------------------------------------------------------------
    |
    | When your application is in debug mode, detailed error messages with
    | stack traces will be shown on every error that occurs within your
    | application. If disabled, a simple generic error page is shown.
    |
    */

    'debug' => (bool) env('APP_DEBUG', false),

    /*
    |--------------------------------------------------------------------------
    | Application URL
    |--------------------------------------------------------------------------
    |
    | This URL is used by the console to properly generate URLs when using
    | the Artisan command line tool. You should set this to the root of
    | your application so that it is used when running Artisan tasks.
    |
    */

    'url' => env('APP_URL', 'http://localhost'),

    'asset_url' => env('ASSET_URL', '/'),

    /*
    |--------------------------------------------------------------------------
    | Application Timezone
    |--------------------------------------------------------------------------
    |
    | Here you may specify the default timezone for your application, which
    | will be used by the PHP date and date-time functions. We have gone
    | ahead and set this to a sensible default for you out of the box.
    |
    */

    'timezone' => 'UTC',

    /*
    |--------------------------------------------------------------------------
    | Application Locale Configuration
    |--------------------------------------------------------------------------
    |
    | The application locale determines the default locale that will be used
    | by the translation service provider. You are free to set this value
    | to any of the locales which will be supported by the application.
    |
    */

    'locale' => 'en',

    /*
    |--------------------------------------------------------------------------
    | Application Fallback Locale
    |--------------------------------------------------------------------------
    |
    | The fallback locale determines the locale to use when the current one
    | is not available. You may change the value to correspond to any of
    | the language folders that are provided through your application.
    |
    */

    'fallback_locale' => 'en',

    /*
    |--------------------------------------------------------------------------
    | Faker Locale
    |--------------------------------------------------------------------------
    |
    | This locale will be used by the Faker PHP library when generating fake
    | data for your database seeds. For example, this will be used to get
    | localized telephone numbers, street address information and more.
    |
    */

    'faker_locale' => 'en_US',

    /*
    |--------------------------------------------------------------------------
    | Encryption Key
    |--------------------------------------------------------------------------
    |
    | This key is used by the Illuminate encrypter service and should be set
    | to a random, 32 character string, otherwise these encrypted strings
    | will not be safe. Please do this before deploying an application!
    |
    */

    'key' => env('APP_KEY'),

    'cipher' => 'AES-256-CBC',

    /*
    |--------------------------------------------------------------------------
    | Maintenance Mode Driver
    |--------------------------------------------------------------------------
    |
    | These configuration options determine the driver used to determine and
    | manage Laravel's "maintenance mode" status. The "cache" driver will
    | allow maintenance mode to be controlled across multiple machines.
    |
    | Supported drivers: "file", "cache"
    |
    */

    'maintenance' => [
        'driver' => 'file',
        // 'store'  => 'redis',
    ],

    /*
    |--------------------------------------------------------------------------
    | Autoloaded Service Providers
    |--------------------------------------------------------------------------
    |
    | The service providers listed here will be automatically loaded on the
    | request to your application. Feel free to add your own services to
    | this array to grant expanded functionality to your applications.
    |
    */

    'providers' => [

        /*
         * Laravel Framework Service Providers...
         */
        Illuminate\Auth\AuthServiceProvider::class,
        Illuminate\Broadcasting\BroadcastServiceProvider::class,
        Illuminate\Bus\BusServiceProvider::class,
        Illuminate\Cache\CacheServiceProvider::class,
        Illuminate\Foundation\Providers\ConsoleSupportServiceProvider::class,
        Illuminate\Cookie\CookieServiceProvider::class,
        Illuminate\Database\DatabaseServiceProvider::class,
        Illuminate\Encryption\EncryptionServiceProvider::class,
        Illuminate\Filesystem\FilesystemServiceProvider::class,
        Illuminate\Foundation\Providers\FoundationServiceProvider::class,
        Illuminate\Hashing\HashServiceProvider::class,
        Illuminate\Mail\MailServiceProvider::class,
        Illuminate\Notifications\NotificationServiceProvider::class,
        Illuminate\Pagination\PaginationServiceProvider::class,
        Illuminate\Pipeline\PipelineServiceProvider::class,
        Illuminate\Queue\QueueServiceProvider::class,
        Illuminate\Redis\RedisServiceProvider::class,
        Illuminate\Auth\Passwords\PasswordResetServiceProvider::class,
        Illuminate\Session\SessionServiceProvider::class,
        Illuminate\Translation\TranslationServiceProvider::class,
        Illuminate\Validation\ValidationServiceProvider::class,
        Illuminate\View\ViewServiceProvider::class,

        /*
         * Package Service Providers...
         */

        /*
         * Application Service Providers...
         */
        App\Providers\AppServiceProvider::class,
        App\Providers\AuthServiceProvider::class,
        // App\Providers\BroadcastServiceProvider::class,
        App\Providers\EventServiceProvider::class,
        App\Providers\RouteServiceProvider::class,
        App\Providers\FortifyServiceProvider::class,
        App\Providers\JetstreamServiceProvider::class,

    ],

    /*
    |--------------------------------------------------------------------------
    | Class Aliases
    |--------------------------------------------------------------------------
    |
    | This array of class aliases will be registered when this application
    | is started. However, feel free to register as many as you wish as
    | the aliases are "lazy" loaded so they don't hinder performance.
    |
    */

    'aliases' => Facade::defaultAliases()->merge([
        // 'ExampleClass' => App\Example\ExampleClass::class,
    ])->toArray(),

];
