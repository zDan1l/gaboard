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
                <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                </svg>
                <div>{{ session('success') }}</div>
            </div>
        </div>
    @endif

    @if(session('error'))
        <div class="bg-red-50 border border-red-200 text-red-800 rounded-lg p-4">
            <div class="flex">
                <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                </svg>
                <div>{{ session('error') }}</div>
            </div>
        </div>
    @endif

    <!-- Today's Attendance Card -->
    <div class="bg-gradient-to-r from-orange-500 to-orange-600 rounded-xl shadow-lg p-8 text-white">
        <div class="flex justify-between items-start mb-6">
            <div>
                <h2 class="text-xl font-semibold mb-1">Hari Ini</h2>
                <p class="text-orange-100">{{ now()->locale('id')->translatedFormat('l, d F Y') }}</p>
            </div>
            @if($todaySchedule)
                <div class="bg-white/20 px-3 py-1 rounded-full text-sm">
                    {{ $todaySchedule->is_working_day ? 'Hari Kerja' : 'Libur' }}
                </div>
            @else)
                <div class="bg-white/20 px-3 py-1 rounded-full text-sm">
                    Tidak ada jadwal
                </div>
            @endif
        </div>

        <div class="grid grid-cols-2 gap-6 mb-8">
            <div class="bg-white/10 rounded-lg p-4 text-center">
                <p class="text-sm text-orange-100 mb-2">Jam Masuk</p>
                <p class="text-3xl font-bold">
                    {{ $todayAttendance?->clock_in_time?->format('H:i') ?? '--:--' }}
                </p>
            </div>
            <div class="bg-white/10 rounded-lg p-4 text-center">
                <p class="text-sm text-orange-100 mb-2">Jam Keluar</p>
                <p class="text-3xl font-bold">
                    {{ $todayAttendance?->clock_out_time?->format('H:i') ?? '--:--' }}
                </p>
            </div>
        </div>

        @if($todaySchedule && $todaySchedule->is_working_day)
            <div class="flex justify-center space-x-4">
                <form method="POST" action="{{ route('attendance-entries.clock-in') }}">
                    @csrf
                    <button type="submit" class="bg-white text-orange-600 px-8 py-3 rounded-lg font-semibold hover:bg-gray-100 transition-colors flex items-center space-x-2" {{ $todayAttendance?->clock_in_time ? 'disabled' : '' }}>
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"/>
                        </svg>
                        <span>{{ $todayAttendance?->clock_in_time ? 'Sudah Absen Masuk' : 'Absen Masuk' }}</span>
                    </button>
                </form>
                <form method="POST" action="{{ route('attendance-entries.clock-out') }}">
                    @csrf
                    <button type="submit" class="bg-gray-800 text-white px-8 py-3 rounded-lg font-semibold hover:bg-gray-900 transition-colors flex items-center space-x-2" {{ !$todayAttendance?->clock_in_time || $todayAttendance?->clock_out_time ? 'disabled' : '' }}>
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7"/>
                        </svg>
                        <span>{{ $todayAttendance?->clock_out_time ? 'Sudah Absen Keluar' : 'Absen Keluar' }}</span>
                    </button>
                </form>
            </div>
        @elseif($todaySchedule && !$todaySchedule->is_working_day)
            <div class="text-center py-4 bg-white/10 rounded-lg">
                <p class="text-orange-100">Hari ini adalah hari libur, tidak perlu absensi.</p>
            </div>
        @else
            <div class="text-center py-4 bg-white/10 rounded-lg">
                <p class="text-orange-100">Belum ada jadwal untuk hari ini. Hubungi HR/Manager.</p>
            </div>
        @endif
    </div>

    <!-- Attendance Statistics -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
        <div class="bg-white shadow-sm rounded-lg p-6 border-l-4 border-green-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600">Hadir</p>
                    <p class="text-3xl font-bold text-gray-900 mt-2">{{ $present }}</p>
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
            <h3 class="text-lg font-semibold text-gray-900">Riwayat Absensi ({{ $attendances->count() }})</h3>
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
                                    {{ $att->schedule?->schedule_date?->locale('id')->translatedFormat('d M Y') }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm">
                                    {{ $att->clock_in_time?->format('H:i') ?? '--:--' }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm">
                                    {{ $att->clock_out_time?->format('H:i') ?? '--:--' }}
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
            <div class="p-8 text-center text-gray-500">Tidak ada data absensi</div>
        @endif
    </div>
</div>
@endsection
