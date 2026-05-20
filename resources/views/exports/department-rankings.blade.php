@extends('layouts.app')

@section('title', 'Ranking Departemen')

@section('content')
<div class="space-y-6">
    <!-- Page Header -->
    <div class="bg-white shadow-sm rounded-lg p-6">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Ranking Departemen</h1>
                <p class="text-sm text-gray-600 mt-1">Peringkat performa departemen</p>
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

    <!-- Rankings Table -->
    <div class="bg-white shadow-sm rounded-lg overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Peringkat</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Departemen</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total Karyawan</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total Evaluasi</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Rata-rata Skor</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Sangat Baik</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Baik</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Cukup</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Buruk</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Sangat Buruk</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($rankings as $index => $department)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium {{ $index === 0 ? 'bg-yellow-100 text-yellow-800' : ($index === 1 ? 'bg-blue-100 text-blue-800' : ($index === 2 ? 'bg-gray-100 text-gray-800' : 'bg-gray-200 text-gray-600')) }}">
                                    #{{ $index + 1 }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-bold text-gray-900">{{ $department->department_name }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $department->total_employees }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $department->total_evaluations }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium {{ $department->average_score >= 0.8 ? 'bg-green-100 text-green-800' : ($department->average_score >= 0.6 ? 'bg-blue-100 text-blue-800' : 'bg-yellow-100 text-yellow-800') }}">
                                    {{ number_format($department->average_score, 2) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-green-600">{{ $department->sangat_baik_count }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-blue-600">{{ $department->baik_count }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-yellow-600">{{ $department->cukup_count }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-red-600">{{ $department->buruk_count }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">{{ $department->sangat_buruk_count }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <!-- Analysis Cards -->
    <div class="bg-white shadow-sm rounded-lg p-6">
        <h3 class="text-lg font-semibold text-gray-900 mb-4">Analisis Performa Departemen</h3>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            @foreach($rankings as $index => $department)
                <div class="rounded-lg border {{ $index === 0 ? 'border-yellow-300 bg-yellow-50' : 'border-gray-200 bg-white' }} p-4">
                    <h6 class="text-lg font-bold text-gray-900 mb-2">
                        #{{ $index + 1 }} {{ $department->department_name }}
                    </h6>
                    <div class="space-y-1">
                        <p class="text-sm text-gray-600">
                            <span class="font-semibold">Skor:</span> {{ number_format($department->average_score, 2) }}
                        </p>
                        <p class="text-sm text-gray-600">
                            <span class="font-semibold">Evaluasi:</span> {{ $department->total_evaluations }}
                        </p>
                        <p class="text-sm text-gray-600">
                            <span class="font-semibold">Karyawan:</span> {{ $department->total_employees }}
                        </p>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</div>
@endsection