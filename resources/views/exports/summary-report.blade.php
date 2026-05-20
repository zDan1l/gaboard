@extends('layouts.app')

@section('title', 'Laporan Ringkas')

@section('content')
<div class="space-y-6">
    <!-- Page Header -->
    <div class="bg-white shadow-sm rounded-lg p-6">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Laporan Ringkas Penilaian</h1>
                <p class="text-sm text-gray-600 mt-1">Ringkasan kinerja perusahaan</p>
            </div>
            <div class="flex flex-wrap gap-2">
                <a href="{{ route('dashboard') }}" class="inline-flex items-center px-4 py-2 bg-gray-600 text-white rounded-lg hover:bg-gray-700 transition-colors">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m7 7l7-7"/>
                    </svg>
                    Kembali ke Dashboard
                </a>
                <button onclick="window.print()" class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v14a2 2 0 002 2h2"/>
                    </svg>
                    Cetak Laporan
                </button>
            </div>
        </div>
    </div>

    <!-- Summary Stats -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div class="bg-white shadow-sm rounded-lg p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Ringkasan Kinerja Perusahaan</h3>
            <div class="grid grid-cols-2 gap-6">
                <div class="text-center">
                    <p class="text-4xl font-bold text-orange-600">{{ $summary['total_evaluations'] }}</p>
                    <p class="text-sm text-gray-600 mt-2">Total Evaluasi</p>
                </div>
                <div class="text-center">
                    <p class="text-4xl font-bold text-green-600">{{ number_format($summary['average_score'], 2) }}</p>
                    <p class="text-sm text-gray-600 mt-2">Rata-rata Skor</p>
                </div>
            </div>
        </div>

        <!-- Performance Distribution -->
        <div class="bg-white shadow-sm rounded-lg p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Distribusi Kinerja</h3>
            <div class="grid grid-cols-5 gap-4">
                <div class="text-center">
                    <p class="text-3xl font-bold text-green-600">{{ $summary['performance_distribution']['sangat_baik'] }}</p>
                    <p class="text-xs text-gray-600 mt-1">Sangat Baik</p>
                </div>
                <div class="text-center">
                    <p class="text-3xl font-bold text-blue-600">{{ $summary['performance_distribution']['baik'] }}</p>
                    <p class="text-xs text-gray-600 mt-1">Baik</p>
                </div>
                <div class="text-center">
                    <p class="text-3xl font-bold text-yellow-600">{{ $summary['performance_distribution']['cukup'] }}</p>
                    <p class="text-xs text-gray-600 mt-1">Cukup</p>
                </div>
                <div class="text-center">
                    <p class="text-3xl font-bold text-red-600">{{ $summary['performance_distribution']['buruk'] }}</p>
                    <p class="text-xs text-gray-600 mt-1">Buruk</p>
                </div>
                <div class="text-center">
                    <p class="text-3xl font-bold text-gray-600">{{ $summary['performance_distribution']['sangat_buruk'] }}</p>
                    <p class="text-xs text-gray-600 mt-1">Sangat Buruk</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Evaluation Details -->
    <div class="bg-white shadow-sm rounded-lg overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200">
            <h3 class="text-lg font-semibold text-gray-900">Detail Evaluasi</h3>
        </div>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Karyawan</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Departemen</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Periode</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Skor Fuzzy</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Kategori</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Rekomendasi</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($evaluations as $evaluation)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $evaluation->employee->user->name }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $evaluation->employee->department->name }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $evaluation->evaluation_period }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-bold text-gray-900">{{ number_format($evaluation->fuzzy_score, 2) }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-{{ $evaluation->performance_class }}-100 text-{{ $evaluation->performance_class }}-800">
                                    {{ $evaluation->category_label }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-600">{{ Str::limit($evaluation->hr_recommendation, 80) }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection