@extends('layouts.app')

@section('title', 'Edit Penilaian')

@section('content')
<div class="space-y-6">
    <!-- Page Header -->
    <div class="bg-white shadow-sm rounded-lg p-6">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Edit Penilaian Kinerja</h1>
                <p class="text-sm text-gray-600 mt-1">Perbarui nilai penilaian karyawan</p>
            </div>
            <div class="flex flex-wrap gap-2">
                <a href="{{ route('evaluations.show', $evaluation) }}" class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                    </svg>
                    Lihat Detail
                </a>
                <a href="{{ route('evaluations.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-600 text-white rounded-lg hover:bg-gray-700 transition-colors">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m7 7l7-7"/>
                    </svg>
                    Kembali
                </a>
            </div>
        </div>
    </div>

    <!-- Employee Info -->
    <div class="bg-white shadow-sm rounded-lg p-6">
        <h3 class="text-lg font-semibold text-gray-900 mb-4">Informasi Karyawan</h3>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div>
                <p class="text-sm text-gray-600">Nama</p>
                <p class="text-base font-medium text-gray-900">{{ $evaluation->employee->user->name }}</p>
            </div>
            <div>
                <p class="text-sm text-gray-600">Jabatan</p>
                <p class="text-base font-medium text-gray-900">{{ $evaluation->employee->position }}</p>
            </div>
            <div>
                <p class="text-sm text-gray-600">Departemen</p>
                <p class="text-base font-medium text-gray-900">{{ $evaluation->employee->department->name }}</p>
            </div>
        </div>
    </div>

    <!-- Edit Form -->
    <div class="bg-white shadow-sm rounded-lg p-6">
        <div class="mb-6">
            <h3 class="text-lg font-semibold text-gray-900">Edit Form Penilaian</h3>
            <p class="text-sm text-gray-600">Update nilai penilaian untuk menghitung ulang skor fuzzy</p>
        </div>

        <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4 mb-6">
            <div class="flex">
                <svg class="w-5 h-5 text-yellow-400 mr-3" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                </svg>
                <div>
                    <h4 class="text-sm font-semibold text-yellow-800">Perhatian</h4>
                    <p class="text-sm text-yellow-700 mt-1">Mengubah nilai penilaian akan menghitung ulang skor fuzzy secara otomatis.</p>
                </div>
            </div>
        </div>

        <form action="{{ route('evaluations.update', $evaluation) }}" method="POST" class="space-y-6">
            @csrf
            @method('PUT')

            <!-- Period -->
            <div>
                <label for="evaluation_period" class="block text-sm font-medium text-gray-700 mb-2">
                    Periode Penilaian <span class="text-red-500">*</span>
                </label>
                <select name="evaluation_period" id="evaluation_period" required
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-orange-500">
                    <option value="">-- Pilih Periode --</option>
                    @foreach($periods as $period)
                        <option value="{{ $period }}" {{ $evaluation->evaluation_period === $period ? 'selected' : '' }}>
                            {{ $period }}
                        </option>
                    @endforeach
                </select>
                @error('evaluation_period')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <hr>
            <h4 class="text-md font-semibold text-gray-900 mb-4">Kriteria Penilaian</h4>

            <!-- Evaluation Criteria -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <!-- KPI Score -->
                <div>
                    <label for="kpi_score" class="block text-sm font-medium text-gray-700 mb-2">
                        KPI Pencapaian (0-100%) <span class="text-red-500">*</span>
                    </label>
                    <input type="number" name="kpi_score" id="kpi_score"
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-orange-500"
                           min="0" max="100" step="0.01" required
                           value="{{ $evaluation->kpi_score }}">
                    @error('kpi_score')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                    <p class="mt-1 text-xs text-gray-500">Nilai saat ini: <strong>{{ number_format($evaluation->kpi_score, 2) }}%</strong></p>
                </div>

                <!-- Attendance Rate -->
                <div>
                    <label for="attendance_rate" class="block text-sm font-medium text-gray-700 mb-2">
                        Tingkat Kehadiran (0-100%) <span class="text-red-500">*</span>
                    </label>
                    <input type="number" name="attendance_rate" id="attendance_rate"
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-orange-500"
                           min="0" max="100" step="0.01" required
                           value="{{ $evaluation->attendance_rate }}">
                    @error('attendance_rate')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                    <p class="mt-1 text-xs text-gray-500">Nilai saat ini: <strong>{{ number_format($evaluation->attendance_rate, 2) }}%</strong></p>
                </div>

                <!-- Customer Satisfaction -->
                <div>
                    <label for="customer_satisfaction" class="block text-sm font-medium text-gray-700 mb-2">
                        Kepuasan Pelanggan (1-10) <span class="text-red-500">*</span>
                    </label>
                    <input type="number" name="customer_satisfaction" id="customer_satisfaction"
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-orange-500"
                           min="1" max="10" step="0.1" required
                           value="{{ $evaluation->customer_satisfaction }}">
                    @error('customer_satisfaction')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                    <p class="mt-1 text-xs text-gray-500">Nilai saat ini: <strong>{{ number_format($evaluation->customer_satisfaction, 1) }}</strong></p>
                </div>
            </div>

            <!-- Notes -->
            <div>
                <label for="notes" class="block text-sm font-medium text-gray-700 mb-2">
                    Catatan Tambahan
                </label>
                <textarea name="notes" id="notes" rows="3"
                          class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-orange-500"
                          placeholder="Masukkan catatan atau observasi tambahan...">{{ $evaluation->notes }}</textarea>
                @error('notes')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Current Result -->
            <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                <h4 class="text-sm font-semibold text-blue-800 mb-2">Hasil Saat Ini</h4>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <p class="text-xs text-blue-600">Skor Fuzzy</p>
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-{{ $evaluation->performance_class }}-100 text-{{ $evaluation->performance_class }}-800">
                            {{ $evaluation->fuzzy_score }}
                        </span>
                    </div>
                    <div>
                        <p class="text-xs text-blue-600">Kategori</p>
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-{{ $evaluation->performance_class }}-100 text-{{ $evaluation->performance_class }}-800">
                            {{ $evaluation->category_label }}
                        </span>
                    </div>
                </div>
            </div>

            <!-- Submit Buttons -->
            <div class="flex items-center justify-between pt-6 border-t border-gray-200">
                <a href="{{ route('evaluations.show', $evaluation) }}" class="inline-flex items-center px-6 py-3 bg-gray-600 text-white rounded-lg hover:bg-gray-700 transition-colors">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                    Batal
                </a>
                <button type="submit" class="inline-flex items-center px-6 py-3 bg-orange-600 text-white rounded-lg hover:bg-orange-700 transition-colors">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                    </svg>
                    Update & Hitung Ulang
                </button>
            </div>
        </form>
    </div>
</div>
@endsection