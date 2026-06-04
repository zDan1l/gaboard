@extends('layouts.app')

@section('title', 'Edit Jadwal Absensi - GaBoard')

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
                <h1 class="text-2xl font-bold text-gray-900">Edit Jadwal Absensi</h1>
                <p class="text-sm text-gray-600 mt-1">
                    {{ $attendanceSchedule->schedule_date->locale('id')->translatedFormat('l, d F Y') }}
                </p>
            </div>
        </div>
    </div>

    <!-- Current Status -->
    <div class="bg-orange-50 border border-orange-200 rounded-lg p-4">
        <div class="flex items-center justify-between">
            <div>
                <span class="text-orange-900 font-medium">Status Saat Ini:</span>
                <span class="ml-2 px-2 py-1 text-xs font-medium rounded-full {{ $attendanceSchedule->is_working_day ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                    {{ $attendanceSchedule->is_working_day ? 'Hari Kerja' : 'Libur' }}
                </span>
            </div>
            @if($attendanceSchedule->schedule_date->isToday())
                <span class="px-2 py-1 text-xs bg-orange-100 text-orange-800 rounded-full">Hari Ini</span>
            @endif
        </div>
    </div>

    <!-- Form -->
    <div class="bg-white shadow-sm rounded-lg overflow-hidden">
        <form method="POST" action="{{ route('attendance-schedules.update', $attendanceSchedule) }}" class="p-6 space-y-6">
            @csrf
            @method('PUT')

            @error('schedule_date')
                <div class="bg-red-50 border border-red-200 text-red-800 rounded-lg p-4">{{ $message }}</div>
            @enderror

            <!-- Schedule Date -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Tanggal *</label>
                <input type="date" name="schedule_date" required value="{{ old('schedule_date', $attendanceSchedule->schedule_date->format('Y-m-d')) }}" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-orange-500 focus:border-orange-500">
                @error('schedule_date')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Title -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Judul</label>
                <input type="text" name="title" value="{{ old('title', $attendanceSchedule->title) }}" placeholder="Contoh: Hari Kerja Reguler" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-orange-500 focus:border-orange-500">
                @error('title')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Description -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Deskripsi</label>
                <textarea name="description" rows="3" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-orange-500 focus:border-orange-500" placeholder="Tambahkan deskripsi atau catatan untuk jadwal ini...">{{ old('description', $attendanceSchedule->description) }}</textarea>
                @error('description')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Working Day Checkbox -->
            <div class="flex items-center">
                <input type="checkbox" name="is_working_day" id="is_working_day" value="1" {{ old('is_working_day', $attendanceSchedule->is_working_day) ? 'checked' : '' }} class="h-4 w-4 text-orange-600 focus:ring-orange-500 border-gray-300 rounded">
                <label for="is_working_day" class="ml-2 block text-sm text-gray-700">
                    Hari kerja (untangkap jika ini adalah hari libur)
                </label>
            </div>
            @error('is_working_day')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror

            <!-- Info Box -->
            <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4">
                <h4 class="font-medium text-yellow-900 mb-2">Perhatian</h4>
                <p class="text-sm text-yellow-800">
                    Mengubah status hari kerja menjadi libur akan mempengaruhi kemampuan karyawan untuk melakukan absensi pada tanggal ini.
                </p>
            </div>

            <!-- Actions -->
            <div class="flex justify-end space-x-3 pt-6 border-t border-gray-200">
                <a href="{{ route('attendance-schedules.index') }}" class="px-6 py-2 border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors">
                    Batal
                </a>
                <form action="{{ route('attendance-schedules.destroy', $attendanceSchedule) }}" method="POST" class="inline" onsubmit="return confirm('Yakin ingin menghapus jadwal ini?')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="px-6 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-colors">
                        Hapus
                    </button>
                </form>
                <button type="submit" class="px-6 py-2 bg-orange-600 text-white rounded-lg hover:bg-orange-700 transition-colors">
                    Update Jadwal
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
