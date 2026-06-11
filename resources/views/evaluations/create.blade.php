@extends('layouts.app')

@section('title', 'Buat Penilaian Baru')

@section('content')
<div class="space-y-6">
    <!-- Page Header -->
    <div class="bg-white shadow-sm rounded-lg p-6">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Buat Penilaian Kinerja Baru</h1>
                <p class="text-sm text-gray-600 mt-1">Input kriteria penilaian karyawan dengan sistem penilaian kinerja</p>
            </div>
            <a href="{{ route('evaluations.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-600 text-white rounded-lg hover:bg-gray-700 transition-colors">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m7 7l7-7"/>
                </svg>
                Kembali
            </a>
        </div>
    </div>

    <!-- Form Input -->
    <div class="bg-white shadow-sm rounded-lg p-6">
        <div class="mb-6">
            <h3 class="text-lg font-semibold text-gray-900">Form Input Penilaian</h3>
            <p class="text-sm text-gray-600">Sistem akan menghitung skor kinerja secara otomatis dari data real karyawan</p>
        </div>

        <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 mb-6">
            <div class="flex">
                <svg class="w-5 h-5 text-blue-400 mr-3" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2V9a1 1 0 00-1-1z" clip-rule="evenodd"/>
                </svg>
                <div>
                    <h4 class="text-sm font-semibold text-blue-800">Sistem Penilaian Otomatis</h4>
                    <p class="text-sm text-blue-600 mt-1">Score dihitung otomatis dari data KPI, kehadiran, dan kepuasan pelanggan yang tersedia dalam rentang tanggal yang dipilih.</p>
                </div>
            </div>
        </div>

        <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4 mb-6">
            <div class="flex">
                <svg class="w-5 h-5 text-yellow-400 mr-3" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                </svg>
                <div>
                    <h4 class="text-sm font-semibold text-yellow-800">Validasi Ketat</h4>
                    <p class="text-sm text-yellow-600 mt-1">Periode penilaian maksimal 1 tahun. Sistem akan menolak jika karyawan tidak memiliki data lengkap dalam periode tersebut.</p>
                </div>
            </div>
        </div>

        <form action="{{ route('evaluations.store') }}" method="POST" class="space-y-6" id="evaluationForm">
            @csrf

            <!-- Employee Selection -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="employee_id" class="block text-sm font-medium text-gray-700 mb-2">
                        Pilih Karyawan <span class="text-red-500">*</span>
                    </label>
                    <select name="employee_id" id="employee_id" required
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-orange-500">
                        <option value="">-- Pilih Karyawan --</option>
                        @foreach($employees as $employee)
                            <option value="{{ $employee->id }}">
                                {{ $employee->user->name }} - {{ $employee->position }}
                                ({{ $employee->department->name }})
                            </option>
                        @endforeach
                    </select>
                    @error('employee_id')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                    <p class="mt-1 text-xs text-gray-500">Pilih karyawan yang akan dinilai</p>
                </div>

                <div>
                    <label for="start_date" class="block text-sm font-medium text-gray-700 mb-2">
                        Tanggal Mulai Periode <span class="text-red-500">*</span>
                    </label>
                    <input type="date" name="start_date" id="start_date" required
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-orange-500">
                    @error('start_date')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                    <p class="mt-1 text-xs text-gray-500">Tanggal awal periode penilaian (format: YYYY-MM-DD)</p>
                </div>

                <div>
                    <label for="end_date" class="block text-sm font-medium text-gray-700 mb-2">
                        Tanggal Akhir Periode <span class="text-red-500">*</span>
                    </label>
                    <input type="date" name="end_date" id="end_date" required
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-orange-500">
                    @error('end_date')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                    <p class="mt-1 text-xs text-gray-500">Tanggal akhir periode penilaian (format: YYYY-MM-DD, maksimal 1 tahun)</p>
                </div>
            </div>

            <!-- Notes -->
            <div>
                <label for="notes" class="block text-sm font-medium text-gray-700 mb-2">
                    Catatan Tambahan
                </label>
                <textarea name="notes" id="notes" rows="3"
                          class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-orange-500"
                          placeholder="Masukkan catatan atau observasi tambahan (opsional)...">{{ old('notes') }}</textarea>
                @error('notes')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Submit Buttons -->
            <div class="flex items-center justify-between pt-6 border-t border-gray-200">
                <a href="{{ route('evaluations.index') }}" class="inline-flex items-center px-6 py-3 bg-gray-600 text-white rounded-lg hover:bg-gray-700 transition-colors">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                    Batal
                </a>
                <button type="submit" class="inline-flex items-center px-6 py-3 bg-orange-600 text-white rounded-lg hover:bg-orange-700 transition-colors">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                    </svg>
                    Simpan & Hitung Skor Kinerja
                </button>
            </div>
        </form>
    </div>

    <!-- Guide -->
    <div class="bg-white shadow-sm rounded-lg p-6">
        <h3 class="text-lg font-semibold text-gray-900 mb-4">Panduan Skor Kinerja</h3>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Performance Categories -->
            <div>
                <h4 class="text-md font-semibold text-gray-700 mb-3">Kategori Kinerja</h4>
                <div class="space-y-2">
                    @foreach([
                        ['0.85 - 1.00', 'Sangat Baik', 'success'],
                        ['0.65 - 0.84', 'Baik', 'primary'],
                        ['0.40 - 0.64', 'Cukup', 'warning'],
                        ['0.20 - 0.39', 'Buruk', 'danger'],
                        ['0.00 - 0.19', 'Sangat Buruk', 'dark'],
                    ] as $range)
                        <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                            <div class="flex-1">
                                <div class="text-sm font-medium text-gray-900">{{ $range[0] }}</div>
                                <div class="text-xs text-gray-500">{{ $range[1] }}</div>
                            </div>
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-{{ $range[2] }}-100 text-{{ $range[2] }}-700">
                                {{ $range[1] }}
                            </span>
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- Input Categories -->
            <div>
                <h4 class="text-md font-semibold text-gray-700 mb-3">Kategori Input</h4>
                <div class="space-y-2 text-sm">
                    <div class="flex justify-between py-2 border-b border-gray-200">
                        <span class="font-medium">KPI (%)</span>
                        <div class="flex gap-4 text-xs">
                            <span>Rendah: 0-60</span>
                            <span>Sedang: 50-85</span>
                            <span class="font-semibold text-green-600">Tinggi: 78-100</span>
                        </div>
                    </div>
                    <div class="flex justify-between py-2 border-b border-gray-200">
                        <span class="font-medium">Kehadiran (%)</span>
                        <div class="flex gap-4 text-xs">
                            <span>Rendah: 0-80</span>
                            <span>Sedang: 75-95</span>
                            <span class="font-semibold text-green-600">Tinggi: 90-100</span>
                        </div>
                    </div>
                    <div class="flex justify-between py-2">
                        <span class="font-medium">Kepuasan (1-10)</span>
                        <div class="flex gap-4 text-xs">
                            <span>Rendah: 1-5.5</span>
                            <span>Sedang: 4.5-8</span>
                            <span class="font-semibold text-green-600">Tinggi: 7-10</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
