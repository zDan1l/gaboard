@extends('layouts.app')

@section('title', 'Buat Jadwal Absensi - GaBoard')

@section('content')
<div class="space-y-6">
    <!-- Page Header -->
    <div class="bg-white shadow-sm rounded-lg p-6">
        <div class="flex items-center gap-4">
            <a href="{{ route('attendance-schedules.index') }}" class="text-gray-400 hover:text-gray-600">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                </svg>
            </a>
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Buat Jadwal Absensi</h1>
                <p class="text-sm text-gray-600 mt-1">Tentukan jadwal hari kerja atau libur</p>
            </div>
        </div>
    </div>

    <!-- Form -->
    <div class="bg-white shadow-sm rounded-lg overflow-hidden">
        <form method="POST" action="{{ route('attendance-schedules.store') }}" class="p-6 space-y-6">
            @csrf

            @error('schedule_date')
                <div class="bg-red-50 border border-red-200 text-red-800 rounded-lg p-4">{{ $message }}</div>
            @enderror

            <!-- Schedule Date -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Tanggal *</label>
                <input type="date" name="schedule_date" required value="{{ old('schedule_date', request('date')) }}" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-orange-500 focus:border-orange-500">
                @error('schedule_date')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Title -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Judul</label>
                <input type="text" name="title" value="{{ old('title') }}" placeholder="Contoh: Hari Kerja Reguler" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-orange-500 focus:border-orange-500">
                @error('title')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Description -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Deskripsi</label>
                <textarea name="description" rows="3" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-orange-500 focus:border-orange-500" placeholder="Tambahkan deskripsi atau catatan untuk jadwal ini...">{{ old('description') }}</textarea>
                @error('description')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Working Day Checkbox -->
            <div class="flex items-center">
                <input type="checkbox" name="is_working_day" id="is_working_day" value="1" {{ old('is_working_day', '1') ? 'checked' : '' }} class="h-4 w-4 text-orange-600 focus:ring-orange-500 border-gray-300 rounded">
                <label for="is_working_day" class="ml-2 block text-sm text-gray-700">
                    Hari kerja (untangkap jika ini adalah hari libur)
                </label>
            </div>
            @error('is_working_day')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror

            <!-- Info Box -->
            <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                <h4 class="font-medium text-blue-900 mb-2">Informasi</h4>
                <ul class="text-sm text-blue-800 space-y-1">
                    <li>• Jadwal hari kerja memungkinkan karyawan untuk melakukan absensi</li>
                    <li>• Jadwal hari libur akan menonaktifkan tombol absensi pada tanggal tersebut</li>
                    <li>• Jadwal akan berlaku untuk semua karyawan yang terdaftar</li>
                </ul>
            </div>

            <!-- Actions -->
            <div class="flex justify-end space-x-3 pt-6 border-t border-gray-200">
                <a href="{{ route('attendance-schedules.index') }}" class="px-6 py-2 border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors">
                    Batal
                </a>
                <button type="submit" class="px-6 py-2 bg-orange-600 text-white rounded-lg hover:bg-orange-700 transition-colors">
                    Simpan Jadwal
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
