@extends('layouts.auth')

@section('title', 'Login - GaBoard')

@section('content')
<div class="bg-white rounded-2xl shadow-xl p-8">
    <!-- Logo -->
    <div class="text-center mb-8">
        <img src="{{ asset('gaboard-logo.png') }}" alt="GaBoard Logo" class="h-16 mx-auto mb-4">
        <p class="text-gray-500 mt-2">Sistem Penilaian Kinerja Karyawan</p>
    </div>

    <!-- Login Form -->
    <form method="POST" action="{{ route('login') }}" class="space-y-6">
        @csrf

        <!-- Email -->
        <div>
            <label for="email" class="block text-sm font-medium text-gray-700 mb-2">Email</label>
            <input
                id="email"
                type="email"
                name="email"
                value="{{ old('email') }}"
                required
                autofocus
                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-transparent outline-none transition-all @error('email') border-red-500 @enderror"
                placeholder="email@example.com"
            >
            @error('email')
                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <!-- Password -->
        <div>
            <label for="password" class="block text-sm font-medium text-gray-700 mb-2">Password</label>
            <input
                id="password"
                type="password"
                name="password"
                required
                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-transparent outline-none transition-all @error('password') border-red-500 @enderror"
                placeholder="••••••••"
            >
            @error('password')
                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <!-- Remember Me -->
        <div class="flex items-center justify-between">
            <label class="flex items-center">
                <input
                    type="checkbox"
                    name="remember"
                    class="w-4 h-4 text-orange-600 border-gray-300 rounded focus:ring-orange-500"
                >
                <span class="ml-2 text-sm text-gray-600">Ingat saya</span>
            </label>
        </div>

        <!-- Login Button -->
        <button
            type="submit"
            class="w-full bg-orange-600 hover:bg-orange-700 text-white font-medium py-3 px-4 rounded-lg transition-colors duration-200 focus:outline-none focus:ring-2 focus:ring-orange-500 focus:ring-offset-2"
        >
            Masuk
        </button>
    </form>

    <!-- Register Link -->
    <div class="mt-6 text-center">
        <p class="text-sm text-gray-600">
            Belum punya akun?
            <a href="{{ route('register') }}" class="font-medium text-orange-600 hover:text-orange-700">
                Daftar sekarang
            </a>
        </p>
    </div>

    <!-- Demo Credentials -->
    @if(config('app.env') !== 'production')
    <div class="mt-8 p-4 bg-blue-50 border border-blue-200 rounded-lg">
        <p class="text-sm font-medium text-blue-800 mb-2">Demo Credentials:</p>
        <p class="text-xs text-blue-700">Email: manager.jkt@company.com</p>
        <p class="text-xs text-blue-700">Email: aulia.putri@company.com</p>
        <p class="text-xs text-blue-700">Password: password</p>
    </div>
    @endif
</div>
@endsection
