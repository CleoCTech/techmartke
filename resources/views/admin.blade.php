<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">
    <script>
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
        <meta name="robots" content="index, follow">
        <meta property="og:title" content="{{ config('app.company.name', 'TechMart KE') }}">
        <meta property="og:description" content="{{ config('app.metaDescription') }}">
        <meta property="og:image" content="{{ asset('images/og-image.jpg') }}">
        <meta property="og:url" content="{{ url()->current() }}">
        <meta property="og:type" content="website">
        <title inertia>{{ config('app.name', 'TechMart KE') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Favicon -->
        <link rel="icon" href="{{ asset('favicon.ico') }}" type="image/x-icon"/>

        <!-- Scripts -->
        @routes
        @vite(['resources/js/app.js', 'resources/css/app.css'])
        @inertiaHead
    </head>
    <body class="font-inter antialiased bg-slate-100 dark:bg-slate-900 text-slate-600 dark:text-slate-400">
        @inertia
    </body>
</html>
