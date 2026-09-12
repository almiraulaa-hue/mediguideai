<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'MediGuide AI') }}</title>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700&display=swap" rel="stylesheet" />

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans text-gray-900 antialiased">
    <div class="min-h-screen flex flex-col items-center justify-center bg-cream-100 px-4">

        <div class="flex items-center gap-2.5 mb-6">
            <div class="w-11 h-11 bg-forest-800 rounded-2xl flex items-center justify-center text-white text-lg">💊</div>
            <span class="text-forest-800 font-bold text-xl">MediGuide AI</span>
        </div>

        <div class="w-full sm:max-w-md bg-white rounded-3xl shadow-sm overflow-hidden px-6 py-8">
            {{ $slot }}
        </div>

    </div>
</body>
</html>