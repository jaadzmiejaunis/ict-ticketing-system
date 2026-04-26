<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@hasSection('title')@yield('title') | @endif{{ config('app.name', 'ICT Support') }}</title>

    <link rel="icon" type="image/png" href="{{ asset('images/gayacare_logo_black.png') }}">

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600,700,800" rel="stylesheet" />

    <script>
        if (localStorage.getItem('color-theme') === 'dark' || (!('color-theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
            document.documentElement.classList.add('dark');
        } else {
            document.documentElement.classList.remove('dark');
        }
    </script>

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        body { font-family: 'Instrument Sans', sans-serif; }
    </style>
</head>
<body class="bg-gray-50 dark:bg-gray-900 antialiased text-gray-900 dark:text-gray-200 transition-colors duration-300">

    <div class="fixed top-6 right-6 z-50">
        <x-theme-toggle />
    </div>

    <div class="min-h-screen flex flex-col items-center justify-center p-6">
        <div class="mb-10">
            <div class="p-5 bg-white dark:bg-gray-800 rounded-3xl border border-gray-200 dark:border-gray-700 shadow-xl transition-colors flex items-center justify-center">
                <a href="/">
                    <x-application-logo class="w-20 h-20" />
                </a>
            </div>
        </div>

        <div class="w-full max-w-[450px] bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 shadow-2xl rounded-xl p-8 sm:p-10 transition-colors">
            {{ $slot }}
        </div>

        <p class="mt-12 text-[9px] font-bold text-gray-400 dark:text-gray-600 uppercase tracking-[0.5em] transition-colors">
            GAYACARE SUPPORT &copy; {{ date('Y') }}
        </p>
    </div>
</body>
</html>
