<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">
    <script>
        // Default to dark mode if no preference is stored
        if (localStorage.getItem('vueuse-color-scheme') === 'light') {
            document.documentElement.classList.remove('dark');
        }
    </script>
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <meta name="description" content="{{ config('app.metaDescription') }}">
        <meta name="keywords" content="{{ config('app.metaKeywords') }}">
        <meta name="author" content="{{ config('app.metaAuthor') }}">
        <meta name="publisher" content="{{ config('app.metaPublisher') }}">
        <meta name="robots" content="{{ config('app.metaRobots') }}">
        <meta property="og:title" content="{{ config('app.company.name') }}">
        <meta property="og:description" content="{{ config('app.metaDescription') }}">
        <meta property="og:image" content="{{ asset('images/og-image.jpg') }}">
        <meta property="og:url" content="{{ url()->current() }}">
        <meta property="og:type" content="website">
        <meta name="twitter:card" content="summary_large_image">
        <meta name="twitter:title" content="{{ config('app.company.name') }}">
        <meta name="twitter:description" content="{{ config('app.metaDescription') }}">
        <title inertia>{{ config('app.name', 'Laravel') }}</title>


        <!-- Fonts: Manrope (headings) + Inter (prices) + Lexend (body) + Urbanist (buttons) -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=manrope:500,600,700,800|inter:400,500,600,700|lexend:300,400,500|urbanist:500,600,700,800&display=swap" rel="stylesheet" />

        <!-- PWA manifest + theme -->
        <link rel="manifest" href="/build/manifest.webmanifest">
        <meta name="theme-color" content="#000000" media="(prefers-color-scheme: light)">
        <meta name="theme-color" content="#000000" media="(prefers-color-scheme: dark)">
        <meta name="application-name" content="TechMart KE">
        <meta name="mobile-web-app-capable" content="yes">

        <!-- iOS PWA support -->
        <meta name="apple-mobile-web-app-capable" content="yes">
        <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
        <meta name="apple-mobile-web-app-title" content="TechMart KE">
        <link rel="apple-touch-icon" href="/pwa/apple-touch-icon.png">
        <link rel="apple-touch-icon" sizes="192x192" href="/pwa/icon-192.png">

        <!-- Icons -->
        <link rel="icon" type="image/png" sizes="32x32" href="/pwa/favicon-32.png">
        <link rel="icon" type="image/png" sizes="192x192" href="/pwa/icon-192.png">
        <link rel="icon" href="/favicon.ico">

        <!-- css -->
        <!-- Scripts -->
        @routes
        @vite(['resources/js/app.js', 'resources/css/app.css'])
        @inertiaHead
    </head>
    <body class="">
        @inertia

         <!-- scroll-top -->
        <!-- scroll-top end -->

         <!-- js -->

        <!--Start of Tawk.to Script-->
        {{-- <script type="text/javascript">
            var Tawk_API=Tawk_API||{}, Tawk_LoadStart=new Date();
            (function(){
            var s1=document.createElement("script"),s0=document.getElementsByTagName("script")[0];
            s1.async=true;
            s1.src='https://embed.tawk.to/6714b2d82480f5b4f5906d28/1iakdl81s';
            s1.charset='UTF-8';
            s1.setAttribute('crossorigin','*');
            s0.parentNode.insertBefore(s1,s0);
            })();
        </script> --}}
        <!--End of Tawk.to Script-->
    </body>
</html>
