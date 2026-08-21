<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="{{ config('app.direction', 'ltr') }}" data-theme="{{ config('theme.default', 'emerald_slate') }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Admin Login - {{ config('app.name', 'Master Dashboard') }}</title>

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- Alpine.js -->
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <!-- Tailwind CSS Assets -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-theme-main text-theme-main font-sans antialiased min-h-screen flex flex-col justify-between transition-colors duration-200">
    
    <!-- Top Header Bar -->
    <header class="p-6 flex items-center justify-between">
        <div class="flex items-center gap-3">
            <div class="w-9 h-9 rounded-xl bg-theme-primary flex items-center justify-center font-black text-white text-lg shadow-md">
                M
            </div>
            <span class="font-bold text-lg text-theme-main tracking-tight">Master Control</span>
        </div>
    </header>

    <!-- Center Login Box -->
    <main class="flex-1 flex items-center justify-center p-4">
        <div class="w-full max-w-md bg-theme-card border border-theme rounded-2xl p-8 shadow-2xl space-y-6">
            <div class="text-center space-y-1">
                <h2 class="text-2xl font-extrabold text-theme-main tracking-tight">Admin Sign In</h2>
                <p class="text-xs text-theme-muted">Enter your account credentials to access control panel</p>
            </div>

            <!-- Error Notification -->
            @if(session('error'))
                <div class="p-3.5 rounded-xl bg-rose-500/10 border border-rose-500/20 text-rose-500 text-xs font-semibold">
                    {{ session('error') }}
                </div>
            @endif

            @if(session('success'))
                <div class="p-3.5 rounded-xl bg-emerald-500/10 border border-emerald-500/20 text-emerald-500 text-xs font-semibold">
                    {{ session('success') }}
                </div>
            @endif

            <!-- Form -->
            <form method="POST" action="{{ route('admin.login.submit') }}" class="space-y-4">
                @csrf

                <div>
                    <label class="block text-xs font-bold text-theme-muted uppercase mb-1">Email Address</label>
                    <input type="email" name="email" value="{{ old('email', 'admin@admin.com') }}" required autofocus placeholder="admin@admin.com" class="w-full px-4 py-2.5 text-sm rounded-xl bg-theme-main border border-theme text-theme-main focus:outline-hidden focus:ring-2 focus:ring-theme-primary">
                    @error('email')
                        <p class="text-xs text-rose-500 mt-1 font-medium">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-xs font-bold text-theme-muted uppercase mb-1">Password</label>
                    <input type="password" name="password" required value="password" placeholder="••••••••" class="w-full px-4 py-2.5 text-sm rounded-xl bg-theme-main border border-theme text-theme-main focus:outline-hidden focus:ring-2 focus:ring-theme-primary">
                    @error('password')
                        <p class="text-xs text-rose-500 mt-1 font-medium">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex items-center justify-between text-xs">
                    <label class="flex items-center gap-2 cursor-pointer font-medium text-theme-muted">
                        <input type="checkbox" name="remember" class="rounded border-theme text-theme-primary focus:ring-theme-primary">
                        <span>Remember Me</span>
                    </label>

                    <a href="#" class="text-theme-primary font-semibold hover:underline">Forgot Password?</a>
                </div>

                <button type="submit" class="w-full py-3 px-4 rounded-xl bg-theme-primary text-white font-bold text-sm shadow-md hover:bg-theme-primary-hover transition-all">
                    Sign In to Control Panel
                </button>
            </form>

            <div class="pt-2 text-center text-xs text-theme-muted border-t border-theme">
                <p>Default Login: <span class="font-mono text-theme-primary font-bold">admin@admin.com</span> / <span class="font-mono text-theme-primary font-bold">password</span></p>
            </div>
        </div>
    </main>

    <!-- Footer -->
    <footer class="p-6 text-center text-xs text-theme-muted">
        &copy; {{ date('Y') }} {{ config('app.name', 'Master Dashboard') }}. Headless Microservice Architecture.
    </footer>
</body>
</html>
