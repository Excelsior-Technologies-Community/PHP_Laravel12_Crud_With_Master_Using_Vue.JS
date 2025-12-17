<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <!-- Set character encoding -->
        <meta charset="utf-8">

        <!-- Responsive viewport for mobile devices -->
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <!-- Dynamic page title handled by Inertia -->
        <title inertia>{{ config('app.name', 'Laravel') }}</title>

        <!-- Load application fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link
            href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap"
            rel="stylesheet"
        />

        <!-- Make Laravel routes available in JavaScript -->
        @routes

        <!-- Load main JS file and current Vue page using Vite -->
        @vite([
            'resources/js/app.js',
            "resources/js/Pages/{$page['component']}.vue"
        ])

        <!-- Inertia head (title, meta tags, etc.) -->
        @inertiaHead
    </head>

    <body class="font-sans antialiased">
        <!-- Inertia renders Vue pages here -->
        @inertia
    </body>
</html>
