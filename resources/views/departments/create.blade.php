@extends('layouts.app')

@section('title', 'Tambah Departemen')

@section('content')
<div class="space-y-6">
    <!-- Page Header -->
    <div class="bg-white shadow-sm rounded-lg p-6">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Tambah Departemen Baru</h1>
                <p class="text-sm text-gray-600 mt-1">Buat departemen baru</p>
            </div>
            <a href="{{ route('departments.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-600 text-white rounded-lg hover:bg-gray-700 transition-colors">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m7 7l7-7"/>
                </svg>
                Kembali
            </a>
        </div>
    </div>

    <!-- Form -->
    <div class="bg-white shadow-sm rounded-lg p-6">
        <form method="POST" action="{{ route('departments.store') }}" class="space-y-6">
            @csrf

            <div>
                <label for="name" class="block text-sm font-medium text-gray-700 mb-2">Nama Departemen</label>
                <input type="text"
                       class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-orange-500 @error('name') border-red-500 @enderror"
                       id="name"
                       name="name"
                       value="{{ old('name') }}"
                       required>
                @error('name')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="code" class="block text-sm font-medium text-gray-700 mb-2">Kode Departemen</label>
                <input type="text"
                       class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-orange-500 @error('code') border-red-500 @enderror"
                       id="code"
                       name="code"
                       value="{{ old('code') }}"
                       required>
                @error('code')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="location" class="block text-sm font-medium text-gray-700 mb-2">Lokasi</label>
                <input type="text"
                       class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-orange-500 @error('location') border-red-500 @enderror"
                       id="location"
                       name="location"
                       value="{{ old('location') }}">
                @error('location')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="description" class="block text-sm font-medium text-gray-700 mb-2">Deskripsi</label>
                <textarea id="description"
                          name="description"
                          rows="3"
                          class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-orange-500 @error('description') border-red-500 @enderror">{{ old('description') }}</textarea>
                @error('description')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div class="flex items-center justify-between pt-6 border-t border-gray-200">
                <a href="{{ route('departments.index') }}" class="inline-flex items-center px-6 py-3 bg-gray-600 text-white rounded-lg hover:bg-gray-700 transition-colors">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                    Batal
                </a>
                <button type="submit" class="inline-flex items-center px-6 py-3 bg-orange-600 text-white rounded-lg hover:bg-orange-700 transition-colors">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                    </svg>
                    Simpan
                </button>
            </div>
        </form>
    </div>
</div>
@endsection