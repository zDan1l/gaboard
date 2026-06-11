@extends('layouts.app')

@section('title', 'Dashboard Karyawan - GaBoard')

@section('content')
<!-- Page Header -->
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900">Dashboard Karyawan</h1>
        <p class="text-gray-600 mt-2">Selamat datang, {{ $user->name }}!</p>
    </div>

    <!-- Today's Attendance Quick Status -->
    <div class="bg-gradient-to-r {{ $todaySchedule && $todaySchedule->is_working_day ? 'from-blue-500 to-blue-600' : 'from-gray-500 to-gray-600' }} rounded-xl shadow-lg p-6 mb-8 text-white">
        <div class="flex justify-between items-start mb-4">
            <div>
                <h2 class="text-lg font-semibold mb-1">Status Absensi Hari Ini</h2>
                <p class="text-blue-100">{{ now()->locale('id')->translatedFormat('l, d F Y') }}</p>
            </div>
            @if($todaySchedule)
                @if($todaySchedule->is_working_day)
                    <div class="bg-green-500 px-3 py-1 rounded-full text-sm font-semibold">
                        HARI KERJA
                    </div>
                @else
                    <div class="bg-gray-700 px-3 py-1 rounded-full text-sm font-semibold">
                        HARI LIBUR
                    </div>
                @endif
            @else
                <div class="bg-yellow-500 px-3 py-1 rounded-full text-sm font-semibold">
                    TIDAK ADA JADWAL
                </div>
            @endif
        </div>

        <div class="grid grid-cols-2 gap-4 mb-4">
            <div class="bg-white/10 rounded-lg p-3 text-center">
                <p class="text-xs text-blue-100 mb-1">Jam Masuk</p>
                <p class="text-xl font-bold">{{ $todayAttendance?->clock_in_time?->format('H:i') ?? '--:--' }}</p>
            </div>
            <div class="bg-white/10 rounded-lg p-3 text-center">
                <p class="text-xs text-blue-100 mb-1">Jam Keluar</p>
                <p class="text-xl font-bold">{{ $todayAttendance?->clock_out_time?->format('H:i') ?? '--:--' }}</p>
            </div>
        </div>

        <div class="flex justify-between items-center">
            <div class="text-sm">
                @if($todaySchedule && $todaySchedule->is_working_day)
                    @if($todayAttendance && $todayAttendance->clock_in_time && $todayAttendance->clock_out_time)
                        <span class="text-green-300">✓ Absensi selesai</span>
                    @elseif($todayAttendance && $todayAttendance->clock_in_time)
                        <span class="text-yellow-300">⏳ Belum absen keluar</span>
                    @else
                        <span class="text-red-300">⚠️ Belum absen masuk</span>
                    @endif
                @elseif($todaySchedule && !$todaySchedule->is_working_day)
                    <span class="text-gray-300">Tidak perlu absen</span>
                @else
                    <span class="text-yellow-300">Hubungi HR</span>
                @endif
            </div>
            <a href="{{ route('attendance-entries.my-attendance') }}" class="bg-white text-blue-600 px-4 py-2 rounded-lg text-sm font-semibold hover:bg-gray-100 transition-colors">
                Lihat Absensi →
            </a>
        </div>
    </div>

    <!-- Quick Stats -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
        <div class="bg-white shadow-sm rounded-lg p-6 border-l-4 border-blue-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600">Hadir Bulan Ini</p>
                    <p class="text-3xl font-bold text-gray-900 mt-2">{{ $present }}</p>
                </div>
                <div class="h-12 w-12 bg-blue-100 rounded-full flex items-center justify-center">
                    <svg class="h-6 w-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
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
        <div class="bg-white shadow-sm rounded-lg p-6 border-l-4 border-green-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600">Total Penilaian</p>
                    <p class="text-3xl font-bold text-gray-900 mt-2">{{ $myEvaluations->count() }}</p>
                </div>
                <div class="h-12 w-12 bg-green-100 rounded-full flex items-center justify-center">
                    <svg class="h-6 w-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
            </div>
        </div>
    </div>

    @if($myEvaluations->count() > 0)
        @php
            $latestEvaluation = $myEvaluations->first();
            $categoryClass = $latestEvaluation->performance_class;
        @endphp

        <!-- Personal Performance Card -->
        <div class="bg-white rounded-xl shadow-sm p-8 mb-8">
            <div class="flex items-center justify-between mb-8">
                <div>
                    <h2 class="text-2xl font-bold text-gray-900">Hasil Penilaian Kinerja Terbaru</h2>
                    <p class="text-gray-600 mt-1">Periode: {{ $latestEvaluation->evaluation_period }}</p>
                </div>
                @if($user->employee)
                <div class="text-right">
                    <span class="px-4 py-2 {{ $user->employee->status === 'active' ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-700' }} rounded-full text-sm font-medium">
                        {{ strtoupper($user->employee->status) }}
                    </span>
                </div>
                @endif
            </div>

            <!-- Score Display -->
            <div class="text-center mb-8">
                <div class="inline-flex items-center justify-center h-32 w-32 rounded-full bg-gradient-to-br {{ $categoryClass === 'success' ? 'from-green-400 to-green-600' : ($categoryClass === 'primary' ? 'from-blue-400 to-blue-600' : ($categoryClass === 'warning' ? 'from-yellow-400 to-yellow-600' : 'from-red-400 to-red-600')) }} text-white shadow-lg">
                    <div>
                        <p class="text-5xl font-bold">{{ $latestScore }}</p>
                    </div>
                </div>
                <div class="mt-4">
                    <span class="px-6 py-3 bg-{{ $categoryClass }}-100 text-{{ $categoryClass }}-700 rounded-full text-lg font-semibold">
                        {{ $latestEvaluation->category_label }}
                    </span>
                </div>
            </div>

            <!-- Evaluation Details -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                <!-- KPI Achievement -->
                <div class="bg-gray-50 rounded-lg p-6 text-center">
                    <div class="h-16 w-16 bg-blue-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6" />
                        </svg>
                    </div>
                    <p class="text-sm font-medium text-gray-600 mb-2">KPI Pencapaian</p>
                    <p class="text-3xl font-bold text-gray-900">{{ number_format($latestEvaluation->kpi_score, 1) }}%</p>
                    <p class="text-sm text-blue-600 mt-2">{{ $latestEvaluation->kpi_score >= 85 ? 'Sangat Baik' : ($latestEvaluation->kpi_score >= 70 ? 'Baik' : 'Perlu Perbaikan') }}</p>
                </div>

                <!-- Attendance -->
                <div class="bg-gray-50 rounded-lg p-6 text-center">
                    <div class="h-16 w-16 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <p class="text-sm font-medium text-gray-600 mb-2">Kehadiran</p>
                    <p class="text-3xl font-bold text-gray-900">{{ number_format($latestEvaluation->attendance_rate, 1) }}%</p>
                    <p class="text-sm text-green-600 mt-2">{{ $latestEvaluation->attendance_rate >= 90 ? 'Sangat Baik' : ($latestEvaluation->attendance_rate >= 80 ? 'Baik' : 'Perlu Perbaikan') }}</p>
                </div>

                <!-- Customer Satisfaction -->
                <div class="bg-gray-50 rounded-lg p-6 text-center">
                    <div class="h-16 w-16 bg-yellow-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-yellow-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z" />
                        </svg>
                    </div>
                    <p class="text-sm font-medium text-gray-600 mb-2">Kepuasan Pelanggan</p>
                    <p class="text-3xl font-bold text-gray-900">{{ number_format($latestEvaluation->customer_satisfaction, 1) }}</p>
                    <p class="text-sm text-yellow-600 mt-2">{{ $latestEvaluation->customer_satisfaction >= 8 ? 'Sangat Baik' : ($latestEvaluation->customer_satisfaction >= 6 ? 'Baik' : 'Perlu Perbaikan') }}</p>
                </div>
            </div>

            <!-- HR Recommendation -->
            <div class="bg-{{ $categoryClass }}-50 border-l-4 border-{{ $categoryClass }}-500 p-6 rounded-lg">
                <div class="flex items-start">
                    <div class="flex-shrink-0">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-{{ $categoryClass }}-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <div class="ml-4">
                        <h4 class="text-lg font-semibold text-{{ $categoryClass }}-900 mb-2">Rekomendasi HR</h4>
                        <p class="text-{{ $categoryClass }}-800">{{ $latestEvaluation->hr_recommendation }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Performance History -->
        @if($myEvaluations->count() > 1)
            <div class="bg-white rounded-xl shadow-sm p-6 mb-8">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Riwayat Kinerja</h3>
                <div class="space-y-4">
                    @foreach($performanceTrend as $evaluation)
                        <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg">
                            <div>
                                <p class="font-medium text-gray-900">{{ $evaluation->evaluation_period }}</p>
                                <p class="text-sm text-gray-500">{{ $evaluation->created_at->format('d F Y') }}</p>
                            </div>
                            <div class="text-right">
                                <p class="text-lg font-bold text-{{ $evaluation->performance_class }}-600">{{ $evaluation->fuzzy_score ?? 'N/A' }}</p>
                                <span class="px-3 py-1 bg-{{ $evaluation->performance_class }}-100 text-{{ $evaluation->performance_class }}-700 text-xs font-medium rounded-full">
                                    {{ $evaluation->category_label }}
                                </span>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif

        <!-- Performance Statistics -->
        <div class="bg-white rounded-xl shadow-sm p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Statistik Kinerja</h3>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div class="text-center">
                    <p class="text-sm font-medium text-gray-600 mb-2">Total Penilaian</p>
                    <p class="text-3xl font-bold text-gray-900">{{ $myEvaluations->count() }}</p>
                </div>
                <div class="text-center">
                    <p class="text-sm font-medium text-gray-600 mb-2">Rata-rata Skor</p>
                    <p class="text-3xl font-bold text-gray-900">{{ $averageScore }}</p>
                </div>
                <div class="text-center">
                    <p class="text-sm font-medium text-gray-600 mb-2">Rekomendasi Aktif</p>
                    <p class="text-3xl font-bold text-gray-900">{{ $recommendations }}</p>
                </div>
            </div>
        </div>
    @else
        <!-- No Evaluations Yet -->
        <div class="bg-white rounded-xl shadow-sm p-12 text-center">
            <div class="h-24 w-24 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-6">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                </svg>
            </div>
            <h3 class="text-xl font-semibold text-gray-900 mb-2">Belum Ada Penilaian</h3>
            <p class="text-gray-600 mb-6">Anda belum memiliki penilaian kinerja. Silakan hubungi HR atau atasan Anda untuk informasi lebih lanjut.</p>
        </div>
    @endif
@endsection
