<!DOCTYPE html>
<html lang="<?php echo e(str_replace('_', '-', app()->getLocale())); ?>">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
        <meta name="description" content="<?php echo e(config('app.metaDescription')); ?>">
        <meta name="keywords" content="<?php echo e(config('app.metaKeywords')); ?>">
        <meta name="author" content="<?php echo e(config('app.metaAuthor')); ?>">
        <meta name="robots" content="index, follow">
        <meta property="og:title" content="Divinely Called Ministries - Kenya">
        <meta property="og:description" content="<?php echo e(config('app.metaDescription')); ?>">
        <meta property="og:image" content="<?php echo e(asset('images/og-image.jpg')); ?>">
        <meta property="og:url" content="<?php echo e(url()->current()); ?>">
        <meta property="og:type" content="website">
        <meta name="twitter:card" content="summary_large_image">
        <meta name="twitter:title" content="Divinely Called Ministries - Kenya">
        <meta name="twitter:description" content="<?php echo e(config('app.metaDescription')); ?>">
        <title inertia><?php echo e(config('app.name', 'Laravel')); ?></title>

        <!-- Fonts -->
        <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap">
        <link rel="stylesheet" href="<?php echo e(asset('assets/css/style.css')); ?>" />
        
       
        <!-- Favicon -->
        <link rel="icon" href="<?php echo e(asset('favicon.ico')); ?>" type="image/x-icon"/>

        <!-- Scripts -->
        <?php echo app('Tighten\Ziggy\BladeRouteGenerator')->generate(); ?>
        
        
        <?php echo app('Illuminate\Foundation\Vite')(['resources/js/app.js', 'resources/css/app.css']); ?>
        <?php if (!isset($__inertiaSsrDispatched)) { $__inertiaSsrDispatched = true; $__inertiaSsrResponse = app(\Inertia\Ssr\Gateway::class)->dispatch($page); }  if ($__inertiaSsrResponse) { echo $__inertiaSsrResponse->head; } ?>
    </head>
    <body class="font-inter antialiased bg-slate-100 dark:bg-slate-900 text-slate-600 dark:text-slate-400 sidebar-expanded">
        <?php if (!isset($__inertiaSsrDispatched)) { $__inertiaSsrDispatched = true; $__inertiaSsrResponse = app(\Inertia\Ssr\Gateway::class)->dispatch($page); }  if ($__inertiaSsrResponse) { echo $__inertiaSsrResponse->body; } else { ?><div id="app" data-page="<?php echo e(json_encode($page)); ?>"></div><?php } ?>
        <div class="layout-wrapper layout-content-navbar">
           
            <div class="layout-overlay layout-menu-toggle"></div>
        </div>
        
    </body>
</html>
<?php /**PATH /Users/ceo/Code/MRH/resources/views/admin.blade.php ENDPATH**/ ?>