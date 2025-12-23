<!DOCTYPE html>
<html lang="<?php echo e(str_replace('_', '-', app()->getLocale())); ?>">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
        <meta name="description" content="<?php echo e(config('app.metaDescription')); ?>">
        <meta name="keywords" content="<?php echo e(config('app.metaKeywords')); ?>">
        <meta name="author" content="<?php echo e(config('app.metaAuthor')); ?>">
        <meta name="publisher" content="<?php echo e(config('app.metaPublisher')); ?>">
        <meta name="robots" content="<?php echo e(config('app.metaRobots')); ?>">
        <meta property="og:title" content="<?php echo e(config('app.company.name')); ?>">
        <meta property="og:description" content="<?php echo e(config('app.metaDescription')); ?>">
        <meta property="og:image" content="<?php echo e(asset('images/og-image.jpg')); ?>">
        <meta property="og:url" content="<?php echo e(url()->current()); ?>">
        <meta property="og:type" content="website">
        <meta name="twitter:card" content="summary_large_image">
        <meta name="twitter:title" content="<?php echo e(config('app.company.name')); ?>">
        <meta name="twitter:description" content="<?php echo e(config('app.metaDescription')); ?>">
        <title inertia><?php echo e(config('app.name', 'Laravel')); ?></title>


        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- css -->
        <!-- Scripts -->
        <?php echo app('Tighten\Ziggy\BladeRouteGenerator')->generate(); ?>
        <?php echo app('Illuminate\Foundation\Vite')(['resources/js/app.js', 'resources/css/app.css']); ?>
        <?php if (!isset($__inertiaSsrDispatched)) { $__inertiaSsrDispatched = true; $__inertiaSsrResponse = app(\Inertia\Ssr\Gateway::class)->dispatch($page); }  if ($__inertiaSsrResponse) { echo $__inertiaSsrResponse->head; } ?>
    </head>
    <body class="">
        <?php if (!isset($__inertiaSsrDispatched)) { $__inertiaSsrDispatched = true; $__inertiaSsrResponse = app(\Inertia\Ssr\Gateway::class)->dispatch($page); }  if ($__inertiaSsrResponse) { echo $__inertiaSsrResponse->body; } else { ?><div id="app" data-page="<?php echo e(json_encode($page)); ?>"></div><?php } ?>

         <!-- scroll-top -->
        <!-- scroll-top end -->

         <!-- js -->

        <!--Start of Tawk.to Script-->
        
        <!--End of Tawk.to Script-->
    </body>
</html>
<?php /**PATH /Users/ceo/Code/MRH/resources/views/app.blade.php ENDPATH**/ ?>