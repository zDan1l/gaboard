@extends('layouts.auth')

@section('title', 'Daftar - GaBoard')

@section('content')
<div class="bg-white rounded-2xl shadow-xl p-8">
    <!-- Logo -->
    <div class="text-center mb-8">
        <h1 class="text-4xl font-bold text-orange-600">GaBoard</h1>
        <p class="text-gray-500 mt-2">Daftar Akun Baru</p>
    </div>

    <!-- Register Form -->
    <form method="POST" action="{{ route('register') }}" class="space-y-6">
        @csrf

        <!-- Name -->
        <div>
            <label for="name" class="block text-sm font-medium text-gray-700 mb-2">Nama Lengkap</label>
            <input
                id="name"
                type="text"
                name="name"
                value="{{ old('name') }}"
                required
                autofocus
                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-transparent outline-none transition-all @error('name') border-red-500 @enderror"
                placeholder="John Doe"
            >
            @error('name')
                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <!-- Email -->
        <div>
            <label for="email" class="block text-sm font-medium text-gray-700 mb-2">Email</label>
            <input
                id="email"
                type="email"
                name="email"
                value="{{ old('email') }}"
                required
                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-transparent outline-none transition-all @error('email') border-red-500 @enderror"
                placeholder="email@example.com"
            >
            @error('email')
                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <!-- Role -->
        <div>
            <label for="role_id" class="block text-sm font-medium text-gray-700 mb-2">Role</label>
            <select
                id="role_id"
                name="role_id"
                required
                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-transparent outline-none transition-all @error('role_id') border-red-500 @enderror"
            >
                <option value="">Pilih Role</option>
                @foreach($roles as $role)
                    <option value="{{ $role->id }}" {{ old('role_id') == $role->id ? 'selected' : '' }}>
                        {{ $role->name }} - {{ $role->description }}
                    </option>
                @endforeach
            </select>
            @error('role_id')
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

        <!-- Confirm Password -->
        <div>
            <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-2">Konfirmasi Password</label>
            <input
                id="password_confirmation"
                type="password"
                name="password_confirmation"
                required
                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-transparent outline-none transition-all"
                placeholder="••••••••"
            >
        </div>

        <!-- Register Button -->
        <button
            type="submit"
            class="w-full bg-orange-600 hover:bg-orange-700 text-white font-medium py-3 px-4 rounded-lg transition-colors duration-200 focus:outline-none focus:ring-2 focus:ring-orange-500 focus:ring-offset-2"
        >
            Daftar
        </button>
    </form>

    <!-- Login Link -->
    <div class="mt-6 text-center">
        <p class="text-sm text-gray-600">
            Sudah punya akun?
            <a href="{{ route('login') }}" class="font-medium text-orange-600 hover:text-orange-700">
                Masuk
            </a>
        </p>
    </div>
</div>
@endsection
