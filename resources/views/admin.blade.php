<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="description" content="{{config('app.metaDescription')}}">
        <meta name="keywords" content="{{config('app.metaKeywords')}}">
        <meta name="author" content="{{config('app.metaAuthor')}}">
        <meta name="robots" content="index, follow">
        <meta property="og:title" content="Divinely Called Ministries - Kenya">
        <meta property="og:description" content="{{config('app.metaDescription')}}">
        <meta property="og:image" content="{{ asset('images/og-image.jpg') }}">
        <meta property="og:url" content="{{ url()->current() }}">
        <meta property="og:type" content="website">
        <meta name="twitter:card" content="summary_large_image">
        <meta name="twitter:title" content="Divinely Called Ministries - Kenya">
        <meta name="twitter:description" content="{{config('app.metaDescription')}}">
        <title inertia>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap">
        <link rel="stylesheet" href="{{ asset('assets/css/style.css')}}" />
        {{-- <link rel="stylesheet" href="{{ asset('assets/css/app.css')}}" /> --}}
       
        <!-- Favicon -->
        <link rel="icon" href="{{ asset('favicon.ico') }}" type="image/x-icon"/>

        <!-- Scripts -->
        @routes
        {{-- @vite(['resources/js/app.js', "resources/js/Pages/{$page['component']}.vue"]) --}}
        {{-- @vite(['resources/js/app.js']) --}}
        @vite(['resources/js/app.js', 'resources/css/app.css'])
        @inertiaHead
    </head>
    <body class="font-inter antialiased bg-slate-100 dark:bg-slate-900 text-slate-600 dark:text-slate-400 sidebar-expanded">
        @inertia
        <div class="layout-wrapper layout-content-navbar">
           
            <div class="layout-overlay layout-menu-toggle"></div>
        </div>
        {{-- @env ('local')
            <script src="http://localhost:3000/browser-sync/browser-sync-client.js"></script>
        @endenv --}}
    </body>
</html>
