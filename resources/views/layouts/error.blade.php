@props(['title' => null, 'code' => '404', 'message' => 'Page Not Found'])

<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="{{ config('app.direction', 'ltr') }}" data-theme="{{ config('theme.default', 'emerald_slate') }}" class="h-full">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Master Admin') }} - {{ $code }} {{ $message }}</title>

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">

    <!-- Alpine.js & Assets -->
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-theme-main text-theme-main font-sans antialiased min-h-screen flex flex-col justify-between transition-colors duration-200 relative overflow-hidden">
    
    <!-- Background Ambient Glow -->
    <div class="absolute inset-0 z-0 overflow-hidden pointer-events-none">
        <div class="absolute -top-40 -right-40 w-[600px] h-[600px] rounded-full bg-rose-500/10 blur-[120px]"></div>
        <div class="absolute -bottom-40 -left-40 w-[600px] h-[600px] rounded-full bg-theme-primary/10 blur-[120px]"></div>
    </div>

    <!-- Top Header -->
    <header class="p-6 flex items-center justify-between relative z-10">
        <a href="/" class="flex items-center gap-3">
            <div class="w-10 h-10 rounded-2xl bg-theme-primary flex items-center justify-center font-black text-white text-xl shadow-lg">
                M
            </div>
            <span class="font-extrabold text-lg text-theme-main tracking-tight">{{ config('app.name', 'Master Admin') }}</span>
        </a>
    </header>

    <!-- Error Hero Center Content -->
    <main class="flex-1 flex items-center justify-center p-6 relative z-10">
        <div class="w-full max-w-lg mx-auto text-center space-y-6 bg-theme-card border border-theme p-8 sm:p-12 rounded-3xl shadow-2xl">
            <!-- Big Error Status Code -->
            <div class="relative inline-block">
                <span class="text-7xl sm:text-8xl font-black text-transparent bg-clip-text bg-gradient-to-r from-theme-primary to-indigo-500 tracking-tighter">
                    {{ $code }}
                </span>
            </div>

            <div class="space-y-2">
                <h1 class="text-xl sm:text-2xl font-black text-theme-main tracking-tight">{{ $message }}</h1>
                <p class="text-xs text-theme-muted max-w-md mx-auto leading-relaxed">
                    {{ $slot->isEmpty() ? "The page you are looking for might have been removed, had its name changed, or is temporarily unavailable." : $slot }}
                </p>
            </div>

            <!-- Action Buttons -->
            <div class="pt-4 flex items-center justify-center gap-3">
                <a href="{{ route('admin.dashboard') }}" class="px-6 py-2.5 rounded-xl bg-theme-primary text-white font-bold text-xs shadow-md hover:bg-opacity-90 transition-all flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 00-1-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                    <span>Back to Dashboard</span>
                </a>
            </div>
        </div>
    </main>

    <!-- Footer -->
    <footer class="p-6 text-center text-xs text-theme-muted relative z-10">
        &copy; {{ date('Y') }} {{ config('app.name', 'Master Admin') }}. System Error Management.
    </footer>
</body>
</html>
