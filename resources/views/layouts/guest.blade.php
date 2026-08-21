@props(['title' => null])

<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="{{ config('app.direction', 'ltr') }}" data-theme="{{ config('theme.default', 'emerald_slate') }}" class="h-full">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Master Control Panel') }} {{ $title ? '- ' . $title : '' }}</title>

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- Alpine.js & Assets -->
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-theme-main text-theme-main font-sans antialiased min-h-screen flex flex-col justify-between transition-colors duration-200 relative overflow-x-hidden">
    
    <!-- Decorative Background Glow Effects -->
    <div class="absolute inset-0 z-0 overflow-hidden pointer-events-none">
        <div class="absolute -top-40 -right-40 w-[500px] h-[500px] rounded-full bg-theme-primary/10 blur-[100px]"></div>
        <div class="absolute -bottom-40 -left-40 w-[500px] h-[500px] rounded-full bg-indigo-500/10 blur-[100px]"></div>
    </div>

    <!-- Top Header Bar -->
    <header class="p-6 flex items-center justify-between relative z-10">
        <a href="/" class="flex items-center gap-3 group">
            <div class="w-10 h-10 rounded-2xl bg-theme-primary flex items-center justify-center font-black text-white text-xl shadow-lg group-hover:scale-105 transition-transform">
                M
            </div>
            <div>
                <span class="font-extrabold text-lg text-theme-main leading-tight tracking-tight block">{{ config('app.name', 'Master Admin') }}</span>
                <span class="text-[10px] text-theme-muted font-medium uppercase tracking-wider block">Headless Microservice</span>
            </div>
        </a>
    </header>

    <!-- Main Card Body Container -->
    <main class="flex-1 flex items-center justify-center p-4 sm:p-6 relative z-10">
        <div class="w-full max-w-md bg-theme-card border border-theme rounded-3xl p-6 sm:p-10 shadow-2xl space-y-6">
            {{ $slot }}
        </div>
    </main>

    <!-- Footer -->
    <footer class="p-6 text-center text-xs text-theme-muted relative z-10">
        &copy; {{ date('Y') }} {{ config('app.name', 'Master Control Panel') }}. All rights reserved.
    </footer>
</body>
</html>
