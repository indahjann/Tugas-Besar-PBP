<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans text-gray-900 antialiased m-0 p-0 h-screen overflow-hidden">
        <div class="h-full flex items-center justify-center p-4 bg-gradient-to-br from-blue-50 to-purple-100">
            <div class="w-full max-w-md">
                <!-- Logo -->
                <div class="text-center mb-4">
                    <a href="/" class="inline-flex items-center gap-2 group">
                        <h1 class="text-2xl font-bold bg-gradient-to-r bg-clip-text text-transparent" style="color: #0066cc;">
                            BUKUKU
                        </h1>
                    </a>
                </div>
                
                <!-- Form Card -->
                <div class="bg-white shadow-lg rounded-xl border border-gray-100 p-6">
                    {{ $slot }}
                </div>
            </div>
        </div>
    </body>
</html>