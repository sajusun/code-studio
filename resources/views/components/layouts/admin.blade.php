<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="{{ config('app.direction', 'ltr') }}" data-theme="{{ config('theme.default', 'emerald_slate') }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Master Dashboard') }} - Admin</title>

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- Alpine.js -->
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <!-- CSS / Tailwind Assets -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-theme-main text-theme-main font-sans antialiased min-h-screen flex flex-col transition-colors duration-200"
      x-data="{ 
          isCollapsed: window.innerWidth >= 1024 ? (localStorage.getItem('sidebar_collapsed') === 'true' && window.innerWidth < 1280 ? true : false) : true,
          mobileSidebarOpen: false 
      }">
    
    <div class="flex flex-1 min-h-screen relative">
        <!-- Sidebar Navigation Partial -->
        @include('layouts.partials.sidebar')

        <!-- Main Content Area -->
        <div class="flex-1 flex flex-col min-w-0 min-h-screen">
            <!-- Header Bar Partial -->
            @include('layouts.partials.header')

            <!-- Page Body Slot -->
            <main class="flex-1 p-4 md:p-8 overflow-y-auto">
                {{ $slot }}
            </main>
        </div>
    </div>

    <!-- Status Modal Component -->
    <x-modal.status />

    <!-- SweetAlert Helper -->
    <script>
        function confirmDelete(url) {
            Swal.fire({
                title: 'Are you sure?',
                text: "This record will be permanently deleted!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#ef4444',
                cancelButtonColor: '#64748b',
                confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = url;
                }
            });
        }
    </script>
</body>
</html>
