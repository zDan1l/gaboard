@extends('layouts.app')

@section('title', 'Manager Dashboard - GaBoard')

@section('content')
<!-- Page Header -->
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900">Manager Dashboard</h1>
        <p class="text-gray-600 mt-2">Selamat datang, {{ $user->name }}!</p>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <!-- Team Members -->
        <div class="bg-white rounded-xl shadow-sm p-6 border-l-4 border-blue-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600">Anggota Tim</p>
                    <p class="text-3xl font-bold text-gray-900 mt-2">12</p>
                </div>
                <div class="h-12 w-12 bg-blue-100 rounded-full flex items-center justify-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                    </svg>
                </div>
            </div>
        </div>

        <!-- Pending Evaluations -->
        <div class="bg-white rounded-xl shadow-sm p-6 border-l-4 border-orange-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600">Belum Dinilai</p>
                    <p class="text-3xl font-bold text-gray-900 mt-2">5</p>
                </div>
                <div class="h-12 w-12 bg-orange-100 rounded-full flex items-center justify-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-orange-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
            </div>
        </div>

        <!-- Team Average Score -->
        <div class="bg-white rounded-xl shadow-sm p-6 border-l-4 border-green-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600">Rata-rata Tim</p>
                    <p class="text-3xl font-bold text-gray-900 mt-2">0.72</p>
                </div>
                <div class="h-12 w-12 bg-green-100 rounded-full flex items-center justify-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                    </svg>
                </div>
            </div>
        </div>
    </div>

    <!-- Team Performance -->
    <div class="bg-white rounded-xl shadow-sm p-6 mb-8">
        <div class="flex items-center justify-between mb-6">
            <h3 class="text-lg font-semibold text-gray-900">Performa Tim</h3>
            <button class="px-4 py-2 bg-orange-600 hover:bg-orange-700 text-white text-sm font-medium rounded-lg transition-colors">
                Mulai Penilaian
            </button>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="border-b border-gray-200">
                        <th class="text-left py-3 px-4 text-sm font-semibold text-gray-700">Nama</th>
                        <th class="text-left py-3 px-4 text-sm font-semibold text-gray-700">Posisi</th>
                        <th class="text-left py-3 px-4 text-sm font-semibold text-gray-700">KPI</th>
                        <th class="text-left py-3 px-4 text-sm font-semibold text-gray-700">Kehadiran</th>
                        <th class="text-left py-3 px-4 text-sm font-semibold text-gray-700">Kepuasan</th>
                        <th class="text-left py-3 px-4 text-sm font-semibold text-gray-700">Skor</th>
                        <th class="text-left py-3 px-4 text-sm font-semibold text-gray-700">Status</th>
                        <th class="text-left py-3 px-4 text-sm font-semibold text-gray-700">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <tr class="border-b border-gray-100 hover:bg-gray-50">
                        <td class="py-4 px-4">
                            <div class="flex items-center space-x-3">
                                <div class="h-10 w-10 bg-green-100 rounded-full flex items-center justify-center">
                                    <span class="text-green-600 font-semibold">AS</span>
                                </div>
                                <span class="font-medium text-gray-900">Ahmad Santoso</span>
                            </div>
                        </td>
                        <td class="py-4 px-4 text-sm text-gray-600">Staff Server</td>
                        <td class="py-4 px-4 text-sm text-gray-600">92%</td>
                        <td class="py-4 px-4 text-sm text-gray-600">95%</td>
                        <td class="py-4 px-4 text-sm text-gray-600">8.5</td>
                        <td class="py-4 px-4">
                            <span class="text-sm font-semibold text-green-600">0.92</span>
                        </td>
                        <td class="py-4 px-4">
                            <span class="px-3 py-1 bg-green-100 text-green-700 text-xs font-medium rounded-full">Sangat Baik</span>
                        </td>
                        <td class="py-4 px-4">
                            <button class="text-blue-600 hover:text-blue-800 text-sm font-medium">Lihat</button>
                        </td>
                    </tr>
                    <tr class="border-b border-gray-100 hover:bg-gray-50">
                        <td class="py-4 px-4">
                            <div class="flex items-center space-x-3">
                                <div class="h-10 w-10 bg-blue-100 rounded-full flex items-center justify-center">
                                    <span class="text-blue-600 font-semibold">BP</span>
                                </div>
                                <span class="font-medium text-gray-900">Budi Pratama</span>
                            </div>
                        </td>
                        <td class="py-4 px-4 text-sm text-gray-600">Staff Kasir</td>
                        <td class="py-4 px-4 text-sm text-gray-600">78%</td>
                        <td class="py-4 px-4 text-sm text-gray-600">88%</td>
                        <td class="py-4 px-4 text-sm text-gray-600">7.5</td>
                        <td class="py-4 px-4">
                            <span class="text-sm font-semibold text-blue-600">0.78</span>
                        </td>
                        <td class="py-4 px-4">
                            <span class="px-3 py-1 bg-blue-100 text-blue-700 text-xs font-medium rounded-full">Baik</span>
                        </td>
                        <td class="py-4 px-4">
                            <button class="text-blue-600 hover:text-blue-800 text-sm font-medium">Lihat</button>
                        </td>
                    </tr>
                    <tr class="border-b border-gray-100 hover:bg-gray-50">
                        <td class="py-4 px-4">
                            <div class="flex items-center space-x-3">
                                <div class="h-10 w-10 bg-yellow-100 rounded-full flex items-center justify-center">
                                    <span class="text-yellow-600 font-semibold">CD</span>
                                </div>
                                <span class="font-medium text-gray-900">Citra Dewi</span>
                            </div>
                        </td>
                        <td class="py-4 px-4 text-sm text-gray-600">Staff Server</td>
                        <td class="py-4 px-4 text-sm text-gray-600">65%</td>
                        <td class="py-4 px-4 text-sm text-gray-600">75%</td>
                        <td class="py-4 px-4 text-sm text-gray-600">6.0</td>
                        <td class="py-4 px-4">
                            <span class="text-sm font-semibold text-yellow-600">0.55</span>
                        </td>
                        <td class="py-4 px-4">
                            <span class="px-3 py-1 bg-yellow-100 text-yellow-700 text-xs font-medium rounded-full">Cukup</span>
                        </td>
                        <td class="py-4 px-4">
                            <button class="text-blue-600 hover:text-blue-800 text-sm font-medium">Lihat</button>
                        </td>
                    </tr>
                    <tr class="border-b border-gray-100 hover:bg-gray-50">
                        <td class="py-4 px-4">
                            <div class="flex items-center space-x-3">
                                <div class="h-10 w-10 bg-gray-100 rounded-full flex items-center justify-center">
                                    <span class="text-gray-600 font-semibold">DK</span>
                                </div>
                                <span class="font-medium text-gray-900">Doni Kusuma</span>
                            </div>
                        </td>
                        <td class="py-4 px-4 text-sm text-gray-600">Staff Dapur</td>
                        <td class="py-4 px-4 text-sm text-gray-600">-</td>
                        <td class="py-4 px-4 text-sm text-gray-600">-</td>
                        <td class="py-4 px-4 text-sm text-gray-600">-</td>
                        <td class="py-4 px-4">
                            <span class="text-sm font-medium text-gray-400">-</span>
                        </td>
                        <td class="py-4 px-4">
                            <span class="px-3 py-1 bg-gray-100 text-gray-600 text-xs font-medium rounded-full">Belum</span>
                        </td>
                        <td class="py-4 px-4">
                            <button class="text-orange-600 hover:text-orange-800 text-sm font-medium">Nilai</button>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
@endsection
