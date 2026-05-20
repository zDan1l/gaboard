<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'GaBoard - Login')</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gradient-to-br from-orange-50 to-gray-100 min-h-screen flex items-center justify-center font-sans antialiased">
    <div class="max-w-md w-full mx-4">
        @yield('content')
    </div>

    @stack('scripts')
</body>
</html>
