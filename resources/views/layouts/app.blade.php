<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'GaBoard - Sistem Penilaian Kinerja Karyawan')</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-50 font-sans antialiased">
    <div class="min-h-screen flex">
        <!-- Sidebar -->
        @if(auth()->check())
            @include('partials.sidebar')
        @endif

        <!-- Main Content -->
        <div class="flex-1 flex flex-col @if(auth()->check())ml-64 @endif">
            <!-- Navbar -->
            @if(auth()->check())
                @include('partials.navbar')
            @endif

            <!-- Page Content -->
            <main class="flex-1 @if(auth()->check())p-6 @else p-0 @endif">
                @yield('content')
            </main>

            <!-- Footer -->
            @include('partials.footer')
        </div>
    </div>

    @stack('scripts')
</body>
</html>
