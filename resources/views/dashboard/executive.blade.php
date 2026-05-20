@extends('layouts.app')

@section('title', 'Executive Dashboard - GaBoard')

@section('content')
<!-- Page Header -->
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900">Executive Dashboard</h1>
        <p class="text-gray-600 mt-2">Selamat datang, {{ $user->name }}!</p>
    </div>

    <!-- High-Level Stats -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
        <!-- Total Workforce -->
        <div class="bg-white rounded-xl shadow-sm p-6">
            <p class="text-sm font-medium text-gray-600 mb-2">Total Workforce</p>
            <p class="text-4xl font-bold text-gray-900">542</p>
            <p class="text-sm text-green-600 mt-2">↑ 12% dari bulan lalu</p>
        </div>

        <!-- Company Average -->
        <div class="bg-white rounded-xl shadow-sm p-6">
            <p class="text-sm font-medium text-gray-600 mb-2">Rata-rata Perusahaan</p>
            <p class="text-4xl font-bold text-gray-900">0.71</p>
            <p class="text-sm text-green-600 mt-2">↑ 0.03 dari bulan lalu</p>
        </div>

        <!-- Top Performers -->
        <div class="bg-white rounded-xl shadow-sm p-6">
            <p class="text-sm font-medium text-gray-600 mb-2">Top Performers</p>
            <p class="text-4xl font-bold text-gray-900">68</p>
            <p class="text-sm text-gray-500 mt-2">12.5% dari total</p>
        </div>

        <!-- Need Attention -->
        <div class="bg-white rounded-xl shadow-sm p-6">
            <p class="text-sm font-medium text-gray-600 mb-2">Perlu Perhatian</p>
            <p class="text-4xl font-bold text-gray-900">42</p>
            <p class="text-sm text-red-600 mt-2">↓ 5 dari bulan lalu</p>
        </div>
    </div>

    <!-- Analytics Charts -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
        <!-- Performance Trend -->
        <div class="bg-white rounded-xl shadow-sm p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Tren Kinerja (6 Bulan Terakhir)</h3>
            <div class="space-y-4">
                <div class="flex items-end justify-between h-40 px-4">
                    <div class="flex flex-col items-center">
                        <div class="bg-blue-500 w-12 rounded-t" style="height: 60%"></div>
                        <span class="text-xs text-gray-600 mt-2">Jan</span>
                    </div>
                    <div class="flex flex-col items-center">
                        <div class="bg-blue-500 w-12 rounded-t" style="height: 65%"></div>
                        <span class="text-xs text-gray-600 mt-2">Feb</span>
                    </div>
                    <div class="flex flex-col items-center">
                        <div class="bg-blue-500 w-12 rounded-t" style="height: 62%"></div>
                        <span class="text-xs text-gray-600 mt-2">Mar</span>
                    </div>
                    <div class="flex flex-col items-center">
                        <div class="bg-blue-500 w-12 rounded-t" style="height: 70%"></div>
                        <span class="text-xs text-gray-600 mt-2">Apr</span>
                    </div>
                    <div class="flex flex-col items-center">
                        <div class="bg-blue-500 w-12 rounded-t" style="height: 68%"></div>
                        <span class="text-xs text-gray-600 mt-2">Mei</span>
                    </div>
                    <div class="flex flex-col items-center">
                        <div class="bg-orange-500 w-12 rounded-t" style="height: 71%"></div>
                        <span class="text-xs text-gray-600 mt-2">Jun</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Department Comparison -->
        <div class="bg-white rounded-xl shadow-sm p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Perbandingan Antar Departemen</h3>
            <div class="space-y-4">
                <div>
                    <div class="flex justify-between mb-1">
                        <span class="text-sm font-medium text-gray-700">Pusat</span>
                        <span class="text-sm font-medium text-gray-700">0.76</span>
                    </div>
                    <div class="w-full bg-gray-200 rounded-full h-3">
                        <div class="bg-green-500 h-3 rounded-full" style="width: 76%"></div>
                    </div>
                </div>
                <div>
                    <div class="flex justify-between mb-1">
                        <span class="text-sm font-medium text-gray-700">Gerai Jakarta</span>
                        <span class="text-sm font-medium text-gray-700">0.72</span>
                    </div>
                    <div class="w-full bg-gray-200 rounded-full h-3">
                        <div class="bg-blue-500 h-3 rounded-full" style="width: 72%"></div>
                    </div>
                </div>
                <div>
                    <div class="flex justify-between mb-1">
                        <span class="text-sm font-medium text-gray-700">Gerai Surabaya</span>
                        <span class="text-sm font-medium text-gray-700">0.68</span>
                    </div>
                    <div class="w-full bg-gray-200 rounded-full h-3">
                        <div class="bg-yellow-500 h-3 rounded-full" style="width: 68%"></div>
                    </div>
                </div>
                <div>
                    <div class="flex justify-between mb-1">
                        <span class="text-sm font-medium text-gray-700">Gerai Bandung</span>
                        <span class="text-sm font-medium text-gray-700">0.65</span>
                    </div>
                    <div class="w-full bg-gray-200 rounded-full h-3">
                        <div class="bg-orange-500 h-3 rounded-full" style="width: 65%"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Strategic Recommendations -->
    <div class="bg-white rounded-xl shadow-sm p-6 mb-8">
        <h3 class="text-lg font-semibold text-gray-900 mb-4">Rekomendasi Strategis</h3>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div class="p-4 bg-green-50 border border-green-200 rounded-lg">
                <div class="flex items-center space-x-3 mb-3">
                    <div class="h-8 w-8 bg-green-500 rounded-full flex items-center justify-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6" />
                        </svg>
                    </div>
                    <h4 class="font-semibold text-green-800">Pertahankan</h4>
                </div>
                <p class="text-sm text-green-700">68 karyawan top performers siap dipertimbangkan untuk promosi kuartal depan.</p>
            </div>

            <div class="p-4 bg-yellow-50 border border-yellow-200 rounded-lg">
                <div class="flex items-center space-x-3 mb-3">
                    <div class="h-8 w-8 bg-yellow-500 rounded-full flex items-center justify-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                        </svg>
                    </div>
                    <h4 class="font-semibold text-yellow-800">Develop</h4>
                </div>
                <p class="text-sm text-yellow-700">Program pelatihan perlu diintensifkan untuk 432 karyawan di level cukup/baik.</p>
            </div>

            <div class="p-4 bg-red-50 border border-red-200 rounded-lg">
                <div class="flex items-center space-x-3 mb-3">
                    <div class="h-8 w-8 bg-red-500 rounded-full flex items-center justify-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <h4 class="font-semibold text-red-800">Action Required</h4>
                </div>
                <p class="text-sm text-red-700">42 karyawan performa buruk memerlukan PIP dan evaluasi intensif 30 hari.</p>
            </div>
        </div>
    </div>

    <!-- Export Reports -->
    <div class="bg-white rounded-xl shadow-sm p-6">
        <h3 class="text-lg font-semibold text-gray-900 mb-4">Laporan Eksekutif</h3>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <button class="flex items-center justify-between p-4 border border-gray-200 rounded-lg hover:bg-gray-50 transition-colors">
                <div class="flex items-center space-x-3">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-red-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                    <div class="text-left">
                        <p class="font-medium text-gray-900">Laporan Kinerja Q2</p>
                        <p class="text-sm text-gray-500">Executive Summary</p>
                    </div>
                </div>
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                </svg>
            </button>

            <button class="flex items-center justify-between p-4 border border-gray-200 rounded-lg hover:bg-gray-50 transition-colors">
                <div class="flex items-center space-x-3">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                    </svg>
                    <div class="text-left">
                        <p class="font-medium text-gray-900">Analytics Dashboard</p>
                        <p class="text-sm text-gray-500">Performance Trends</p>
                    </div>
                </div>
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                </svg>
            </button>
        </div>
    </div>
@endsection
