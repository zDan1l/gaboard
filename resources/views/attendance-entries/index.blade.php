@extends('layouts.app')

@section('title', 'Absensi Saya - GaBoard')

@section('content')
<div class="space-y-6">
    <!-- Page Header -->
    <div class="bg-white shadow-sm rounded-lg p-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Absensi Saya</h1>
            <p class="text-sm text-gray-600 mt-1">Catat kehadiran harian Anda</p>
        </div>
    </div>

    @if(session('success'))
        <div class="bg-green-50 border border-green-200 text-green-800 rounded-lg p-4">
            <div class="flex">
                <svg class="w-5 h-5 mr-2 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                </svg>
                <div>{{ session('success') }}</div>
            </div>
        </div>
    @endif

    @if(session('error'))
        <div class="bg-red-50 border border-red-200 text-red-800 rounded-lg p-4">
            <div class="flex">
                <svg class="w-5 h-5 mr-2 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                </svg>
                <div>{{ session('error') }}</div>
            </div>
        </div>
    @endif

    <!-- Today's Attendance Status Card -->
    <div class="bg-gradient-to-r from-orange-500 to-orange-600 rounded-xl shadow-lg p-8 text-white">
        <div class="flex justify-between items-start mb-6">
            <div>
                <h2 class="text-xl font-semibold mb-1">Status Hari Ini</h2>
                <p class="text-orange-100">{{ now()->locale('id')->translatedFormat('l, d F Y') }}</p>
                <p class="text-sm text-orange-200 mt-1">{{ now()->format('H:i') }} WIB</p>
            </div>
            @if($todaySchedule)
                @if($todaySchedule->is_working_day)
                    <div class="bg-green-500 px-4 py-2 rounded-full text-sm font-semibold flex items-center space-x-2">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                        </svg>
                        <span>HARI KERJA - WAJIB ABSEN</span>
                    </div>
                @else
                    <div class="bg-gray-700 px-4 py-2 rounded-full text-sm font-semibold flex items-center space-x-2">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M6 18L18 6M6 6l12 12" clip-rule="evenodd"/>
                        </svg>
                        <span>HARI LIBUR</span>
                    </div>
                @endif
            @else
                <div class="bg-yellow-500 px-4 py-2 rounded-full text-sm font-semibold flex items-center space-x-2">
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                    </svg>
                    <span>TIDAK ADA JADWAL</span>
                </div>
            @endif
        </div>

        <!-- Attendance Status Information -->
        @if($todaySchedule && $todaySchedule->is_working_day)
            @if($todayAttendance && $todayAttendance->clock_in_time && $todayAttendance->clock_out_time)
                <div class="bg-white/10 rounded-lg p-4 mb-6">
                    <div class="flex items-center justify-center space-x-2">
                        <svg class="w-6 h-6 text-green-300" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                        </svg>
                        <span class="font-semibold">Absensi hari ini sudah selesai!</span>
                    </div>
                </div>
            @elseif($todayAttendance && $todayAttendance->clock_in_time && !$todayAttendance->clock_out_time)
                <div class="bg-white/10 rounded-lg p-4 mb-6">
                    <div class="flex items-center justify-center space-x-2">
                        <svg class="w-6 h-6 text-yellow-300" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"/>
                        </svg>
                        <span class="font-semibold">Anda sudah absen masuk, jangan lupa absen keluar!</span>
                    </div>
                </div>
            @else
                <div class="bg-white/10 rounded-lg p-4 mb-6">
                    <div class="flex items-center justify-center space-x-2">
                        <svg class="w-6 h-6 text-blue-300" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                        </svg>
                        <span class="font-semibold">Silakan absen masuk terlebih dahulu</span>
                    </div>
                </div>
            @endif
        @elseif($todaySchedule && !$todaySchedule->is_working_day)
            <div class="bg-white/10 rounded-lg p-4 mb-6">
                <div class="flex items-center justify-center space-x-2">
                    <svg class="w-6 h-6 text-orange-300" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M6 18L18 6M6 6l12 12" clip-rule="evenodd"/>
                    </svg>
                    <span class="font-semibold">Hari ini adalah hari libur, tidak perlu absensi</span>
                </div>
            </div>
        @else
            <div class="bg-white/10 rounded-lg p-4 mb-6">
                <div class="flex items-center justify-center space-x-2">
                    <svg class="w-6 h-6 text-yellow-300" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                    </svg>
                    <span class="font-semibold">Jadwal hari ini belum tersedia. Hubungi HR/Manager.</span>
                </div>
            </div>
        @endif

        <!-- Clock In/Out Times -->
        <div class="grid grid-cols-2 gap-6 mb-8">
            <div class="bg-white/10 rounded-lg p-4 text-center">
                <p class="text-sm text-orange-100 mb-2 flex items-center justify-center space-x-1">
                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"/>
                    </svg>
                    <span>Jam Masuk</span>
                </p>
                <p class="text-3xl font-bold">
                    {{ $todayAttendance?->clock_in_time?->format('H:i') ?? '--:--' }}
                </p>
                @if($todayAttendance?->clock_in_time)
                    <p class="text-xs text-orange-200 mt-1">{{ $todayAttendance->clock_in_time->diffForHumans() }}</p>
                @endif
            </div>
            <div class="bg-white/10 rounded-lg p-4 text-center">
                <p class="text-sm text-orange-100 mb-2 flex items-center justify-center space-x-1">
                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"/>
                    </svg>
                    <span>Jam Keluar</span>
                </p>
                <p class="text-3xl font-bold">
                    {{ $todayAttendance?->clock_out_time?->format('H:i') ?? '--:--' }}
                </p>
                @if($todayAttendance?->clock_out_time)
                    <p class="text-xs text-orange-200 mt-1">{{ $todayAttendance->clock_out_time->diffForHumans() }}</p>
                @endif
            </div>
        </div>

        <!-- Working Hours Display -->
        @if($todayAttendance && $todayAttendance->working_hours)
            <div class="bg-white/10 rounded-lg p-3 mb-6 text-center">
                <p class="text-sm text-orange-100">Total Jam Kerja Hari Ini</p>
                <p class="text-2xl font-bold">{{ number_format($todayAttendance->working_hours, 1) }} jam</p>
            </div>
        @endif

        <!-- Action Buttons -->
        @if($todaySchedule && $todaySchedule->is_working_day)
            <div class="flex justify-center space-x-4">
                <form method="POST" action="{{ route('attendance-entries.clock-in') }}">
                    @csrf
                    <button type="submit"
                        class="px-8 py-3 rounded-lg font-semibold transition-all flex items-center space-x-2 {{ $todayAttendance?->clock_in_time ? 'bg-white/20 text-white cursor-not-allowed' : 'bg-white text-orange-600 hover:bg-gray-100' }}"
                        {{ $todayAttendance?->clock_in_time ? 'disabled' : '' }}>
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"/>
                        </svg>
                        <span>{{ $todayAttendance?->clock_in_time ? '✓ Sudah Masuk' : 'Absen Masuk' }}</span>
                    </button>
                </form>
                <form method="POST" action="{{ route('attendance-entries.clock-out') }}">
                    @csrf
                    <button type="submit"
                        class="px-8 py-3 rounded-lg font-semibold transition-all flex items-center space-x-2 {{ !$todayAttendance?->clock_in_time || $todayAttendance?->clock_out_time ? 'bg-white/20 text-white cursor-not-allowed' : 'bg-gray-800 text-white hover:bg-gray-900' }}"
                        {{ !$todayAttendance?->clock_in_time || $todayAttendance?->clock_out_time ? 'disabled' : '' }}>
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7"/>
                        </svg>
                        <span>{{ $todayAttendance?->clock_out_time ? '✓ Sudah Keluar' : 'Absen Keluar' }}</span>
                    </button>
                </form>
            </div>
        @elseif($todaySchedule && !$todaySchedule->is_working_day)
            <div class="text-center">
                <div class="inline-flex items-center space-x-2 bg-white/10 px-6 py-3 rounded-lg">
                    <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M6 18L18 6M6 6l12 12" clip-rule="evenodd"/>
                    </svg>
                    <span class="font-semibold">Tidak perlu absen pada hari libur</span>
                </div>
            </div>
        @else
            <div class="text-center">
                <a href="mailto:hr@company.com" class="inline-flex items-center space-x-2 bg-yellow-500 text-white px-6 py-3 rounded-lg hover:bg-yellow-600 transition-colors">
                    <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M2.003 5.884L10 9.882l7.997-3.998A2 2 0 0016 4H4a2 2 0 00-1.997 1.884z"/>
                        <path d="M18 8.118l-8 4-8-4V14a2 2 0 002 2h12a2 2 0 002-2V8.118z"/>
                    </svg>
                    <span class="font-semibold">Hubungi HR</span>
                </a>
            </div>
        @endif
    </div>

    <!-- Quick Info Cards -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
            <div class="flex items-start space-x-3">
                <div class="flex-shrink-0">
                    <div class="w-10 h-10 bg-blue-500 rounded-full flex items-center justify-center">
                        <svg class="w-6 h-6 text-white" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                        </svg>
                    </div>
                </div>
                <div class="flex-1">
                    <h4 class="font-semibold text-blue-900">Kapan Harus Absen?</h4>
                    <p class="text-sm text-blue-700 mt-1">
                        @if($todaySchedule && $todaySchedule->is_working_day)
                            Hari ini adalah hari kerja. Wajib absen masuk dan keluar.
                        @elseif($todaySchedule && !$todaySchedule->is_working_day)
                            Hari ini adalah hari libur. Tidak perlu absen.
                        @else
                            Belum ada jadwal. Hubungi HR untuk informasi lebih lanjut.
                        @endif
                    </p>
                </div>
            </div>
        </div>

        <div class="bg-green-50 border border-green-200 rounded-lg p-4">
            <div class="flex items-start space-x-3">
                <div class="flex-shrink-0">
                    <div class="w-10 h-10 bg-green-500 rounded-full flex items-center justify-center">
                        <svg class="w-6 h-6 text-white" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                        </svg>
                    </div>
                </div>
                <div class="flex-1">
                    <h4 class="font-semibold text-green-900">Status Absensi</h4>
                    <p class="text-sm text-green-700 mt-1">
                        @if($todayAttendance && $todayAttendance->clock_in_time && $todayAttendance->clock_out_time)
                            ✓ Absensi lengkap (masuk & keluar)
                        @elseif($todayAttendance && $todayAttendance->clock_in_time)
                            ⏳ Sudah masuk, belum keluar
                        @elseif($todaySchedule && $todaySchedule->is_working_day)
                            ⚠ Belum absen hari ini
                        @else
                            - Tidak perlu absen
                        @endif
                    </p>
                </div>
            </div>
        </div>

        <div class="bg-purple-50 border border-purple-200 rounded-lg p-4">
            <div class="flex items-start space-x-3">
                <div class="flex-shrink-0">
                    <div class="w-10 h-10 bg-purple-500 rounded-full flex items-center justify-center">
                        <svg class="w-6 h-6 text-white" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M9 2a1 1 0 000 2h2a1 1 0 100-2H9z"/>
                            <path fill-rule="evenodd" d="M4 5a2 2 0 012-2 3 3 0 003 3h2a3 3 0 003-3 2 2 0 012 2v11a2 2 0 01-2 2H6a2 2 0 01-2-2V5zm9.707 5.707a1 1 0 00-1.414-1.414L9 12.586l-1.293-1.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                        </svg>
                    </div>
                </div>
                <div class="flex-1">
                    <h4 class="font-semibold text-purple-900">Kehadiran Bulan Ini</h4>
                    <p class="text-sm text-purple-700 mt-1">
                        {{ $present }} hadir, {{ $late }} terlambat, {{ $absent }} absen dari {{ $total }} hari kerja
                    </p>
                </div>
            </div>
        </div>
    </div>

    <!-- Attendance Statistics -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
        <div class="bg-white shadow-sm rounded-lg p-6 border-l-4 border-green-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600">Hadir</p>
                    <p class="text-3xl font-bold text-gray-900 mt-2">{{ $present }}</p>
                    <p class="text-xs text-gray-500 mt-1">{{ $total > 0 ? number_format(($present / $total) * 100, 0) : 0 }}% dari total</p>
                </div>
                <div class="h-12 w-12 bg-green-100 rounded-full flex items-center justify-center">
                    <svg class="h-6 w-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                    </svg>
                </div>
            </div>
        </div>
        <div class="bg-white shadow-sm rounded-lg p-6 border-l-4 border-yellow-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600">Terlambat</p>
                    <p class="text-3xl font-bold text-gray-900 mt-2">{{ $late }}</p>
                    <p class="text-xs text-gray-500 mt-1">{{ $total > 0 ? number_format(($late / $total) * 100, 0) : 0 }}% dari total</p>
                </div>
                <div class="h-12 w-12 bg-yellow-100 rounded-full flex items-center justify-center">
                    <svg class="h-6 w-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
            </div>
        </div>
        <div class="bg-white shadow-sm rounded-lg p-6 border-l-4 border-red-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600">Absen</p>
                    <p class="text-3xl font-bold text-gray-900 mt-2">{{ $absent }}</p>
                    <p class="text-xs text-gray-500 mt-1">{{ $total > 0 ? number_format(($absent / $total) * 100, 0) : 0 }}% dari total</p>
                </div>
                <div class="h-12 w-12 bg-red-100 rounded-full flex items-center justify-center">
                    <svg class="h-6 w-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </div>
            </div>
        </div>
        <div class="bg-white shadow-sm rounded-lg p-6 border-l-4 border-orange-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600">Kehadiran</p>
                    <p class="text-3xl font-bold text-gray-900 mt-2">{{ number_format($rate, 0, ',', '.') }}%</p>
                    <p class="text-xs text-gray-500 mt-1">{{ $present + $late }} dari {{ $total }} hari</p>
                </div>
                <div class="h-12 w-12 bg-orange-100 rounded-full flex items-center justify-center">
                    <svg class="h-6 w-6 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                    </svg>
                </div>
            </div>
        </div>
    </div>

    <!-- Attendance History -->
    <div class="bg-white shadow-sm rounded-lg overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200 flex justify-between items-center">
            <h3 class="text-lg font-semibold text-gray-900">Riwayat Absensi ({{ $attendances->count() }} record)</h3>
            <form method="GET" action="{{ route('attendance-entries.my-attendance') }}">
                <select name="month" onchange="this.form.submit()" class="border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-orange-500 focus:border-orange-500">
                    <option value="">Semua Bulan</option>
                    @for($m = 1; $m <= 12; $m++)
                        @php
                            $months = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];
                            $selected = request('month') == $m ? 'selected' : '';
                        @endphp
                        <option value="{{ $m }}" {{ $selected }}>{{ $months[$m - 1] }} {{ now()->year }}</option>
                    @endfor
                </select>
            </form>
        </div>
        @if($attendances->count() > 0)
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tanggal</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Jam Masuk</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Jam Keluar</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Jam Kerja</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Catatan</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($attendances as $att)
                            @php
                                $statusClass = match($att->status) {
                                    'present' => 'bg-green-100 text-green-800',
                                    'late' => 'bg-yellow-100 text-yellow-800',
                                    'absent' => 'bg-red-100 text-red-800',
                                    'excused' => 'bg-gray-100 text-gray-800',
                                    default => 'bg-gray-100 text-gray-800',
                                };
                                $statusLabel = match($att->status) {
                                    'present' => 'Hadir',
                                    'late' => 'Terlambat',
                                    'absent' => 'Absen',
                                    'excused' => 'Izin',
                                    default => $att->status,
                                };
                            @endphp
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 whitespace-nowrap text-sm">
                                    <div class="font-medium text-gray-900">{{ $att->schedule?->schedule_date?->locale('id')->translatedFormat('l') }}</div>
                                    <div class="text-gray-500">{{ $att->schedule?->schedule_date?->locale('id')->translatedFormat('d M Y') }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm">
                                    {{ $att->clock_in_time?->format('H:i') ?? '--:--' }}
                                    @if($att->clock_in_time)
                                        <div class="text-xs text-gray-400">{{ $att->clock_in_time->diffForHumans() }}</div>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm">
                                    {{ $att->clock_out_time?->format('H:i') ?? '--:--' }}
                                    @if($att->clock_out_time)
                                        <div class="text-xs text-gray-400">{{ $att->clock_out_time->diffForHumans() }}</div>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm">
                                    {{ $att->working_hours ? number_format($att->working_hours, 1) . ' jam' : '-' }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="px-2 py-1 text-xs font-medium rounded-full {{ $statusClass }}">{{ $statusLabel }}</span>
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-500 max-w-xs truncate">
                                    {{ $att->notes ?: '-' }}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <div class="p-12 text-center">
                <div class="h-16 w-16 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                    <svg class="h-8 w-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/>
                    </svg>
                </div>
                <h3 class="text-lg font-medium text-gray-900 mb-2">Belum ada riwayat absensi</h3>
                <p class="text-gray-600">Riwayat absensi Anda akan muncul di sini setelah melakukan absensi.</p>
            </div>
        @endif
    </div>
</div>
@endsection
