<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
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


        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

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
