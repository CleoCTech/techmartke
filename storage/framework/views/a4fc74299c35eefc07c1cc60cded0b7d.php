<!DOCTYPE html>
<html lang="<?php echo e(str_replace('_', '-', app()->getLocale())); ?>" class="dark">
    <script>
        if (localStorage.getItem('vueuse-color-scheme') === 'light') {
            document.documentElement.classList.remove('dark');
        }
    </script>
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
        <meta name="description" content="<?php echo e(config('app.metaDescription')); ?>">
        <meta name="keywords" content="<?php echo e(config('app.metaKeywords')); ?>">
        <meta name="author" content="<?php echo e(config('app.metaAuthor')); ?>">
        <meta name="robots" content="index, follow">
        <meta property="og:title" content="<?php echo e(config('app.company.name', 'TechMart KE')); ?>">
        <meta property="og:description" content="<?php echo e(config('app.metaDescription')); ?>">
        <meta property="og:image" content="<?php echo e(asset('images/og-image.jpg')); ?>">
        <meta property="og:url" content="<?php echo e(url()->current()); ?>">
        <meta property="og:type" content="website">
        <title inertia><?php echo e(config('app.name', 'TechMart KE')); ?></title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Favicon -->
        <link rel="icon" href="<?php echo e(asset('favicon.ico')); ?>" type="image/x-icon"/>
        <link rel="icon" type="image/png" sizes="32x32" href="/pwa/favicon-32.png">
        <link rel="apple-touch-icon" href="/pwa/apple-touch-icon.png">
        <meta name="theme-color" content="#000000">

        <!-- Scripts -->
        <?php echo app('Tighten\Ziggy\BladeRouteGenerator')->generate(); ?>
        <?php echo app('Illuminate\Foundation\Vite')(['resources/js/app.js', 'resources/css/app.css']); ?>
        <?php if (!isset($__inertiaSsrDispatched)) { $__inertiaSsrDispatched = true; $__inertiaSsrResponse = app(\Inertia\Ssr\Gateway::class)->dispatch($page); }  if ($__inertiaSsrResponse) { echo $__inertiaSsrResponse->head; } ?>
    </head>
    <body class="font-inter antialiased bg-slate-100 dark:bg-slate-900 text-slate-600 dark:text-slate-400">
        <?php if (!isset($__inertiaSsrDispatched)) { $__inertiaSsrDispatched = true; $__inertiaSsrResponse = app(\Inertia\Ssr\Gateway::class)->dispatch($page); }  if ($__inertiaSsrResponse) { echo $__inertiaSsrResponse->body; } else { ?><div id="app" data-page="<?php echo e(json_encode($page)); ?>"></div><?php } ?>
    </body>
</html>
<?php /**PATH /Users/ceo/Code/TechMartKe/resources/views/admin.blade.php ENDPATH**/ ?>