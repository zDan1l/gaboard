@extends('layouts.app')

@section('title', 'Laporan KPI Saya - GaBoard')

@section('content')
<div class="space-y-6">
    <!-- Page Header -->
    <div class="bg-white shadow-sm rounded-lg p-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Laporan KPI Saya</h1>
            <p class="text-sm text-gray-600 mt-1">Lihat dan laporkan capaian target KPI Anda</p>
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

    <!-- My KPI Targets Cards -->
    <div>
        <h3 class="text-lg font-semibold text-gray-900 mb-4">Target KPI Aktif ({{ $myTargets->count() }})</h3>
        @if($myTargets->count() > 0)
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($myTargets as $target)
                    @php
                        $latestReport = $target->reports->first();
                        $achievement = $latestReport ? (($latestReport->actual_value / $target->target_value) * 100) : 0;
                        $progressClass = $achievement >= 100 ? 'bg-green-500' : ($achievement >= 70 ? 'bg-yellow-500' : 'bg-red-500');
                    @endphp
                    <div class="bg-white shadow-sm rounded-lg p-6 border-l-4 border-orange-500">
                        <div class="flex justify-between items-start mb-4">
                            <div class="flex-1">
                                <h3 class="font-semibold text-gray-900">{{ $target->title }}</h3>
                                <p class="text-sm text-gray-500 mt-1">{{ $target->description ?: '-' }}</p>
                            </div>
                            <span class="px-2 py-1 text-xs font-medium rounded-full {{ $target->status === 'active' ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">
                                {{ $target->status === 'active' ? 'Aktif' : 'Tidak Aktif' }}
                            </span>
                        </div>
                        <div class="space-y-3 mb-4">
                            <div class="flex justify-between text-sm">
                                <span class="text-gray-600">Target:</span>
                                <span class="font-medium">{{ number_format($target->target_value, 0, ',', '.') }} {{ $target->unit }}</span>
                            </div>
                            <div class="flex justify-between text-sm">
                                <span class="text-gray-600">Periode:</span>
                                <span class="capitalize">
                                    {{ match($target->period) {
                                        'daily' => 'Harian',
                                        'weekly' => 'Mingguan',
                                        'monthly' => 'Bulanan',
                                        'custom' => 'Custom',
                                        default => $target->period,
                                    } }}
                                </span>
                            </div>
                            <div class="flex justify-between text-sm">
                                <span class="text-gray-600">Pencapaian:</span>
                                <span class="font-medium">{{ number_format($achievement, 0, ',', '.') }}%</span>
                            </div>
                            <div class="w-full bg-gray-200 rounded-full h-2">
                                <div class="{{ $progressClass }} h-2 rounded-full transition-all" style="width: {{ min($achievement, 100) }}%"></div>
                            </div>
                        </div>
                        <a href="{{ route('kpi-reports.create', $target) }}" class="block w-full text-center bg-orange-600 text-white py-2 rounded-lg hover:bg-orange-700 transition-colors font-medium">
                            {{ $latestReport ? 'Update Laporan' : 'Buat Laporan' }}
                        </a>
                    </div>
                @endforeach
            </div>
        @else
            <div class="bg-white shadow-sm rounded-lg p-12 text-center">
                <div class="h-16 w-16 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                    <svg class="h-8 w-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/>
                    </svg>
                </div>
                <h3 class="text-lg font-medium text-gray-900 mb-2">Tidak ada target KPI aktif</h3>
                <p class="text-gray-600">Hubungi manager atau HR untuk mendapatkan target KPI.</p>
            </div>
        @endif
    </div>

    <!-- Recent Reports Table -->
    <div class="bg-white shadow-sm rounded-lg overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200">
            <h3 class="text-lg font-semibold text-gray-900">Riwayat Laporan ({{ $myReports->count() }})</h3>
        </div>
        @if($myReports->count() > 0)
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Target</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nilai Target</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Capaian</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Persentase</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tanggal</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Catatan</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($myReports as $report)
                            @php
                                $achievement = $report->achievement_percentage ?? 0;
                                $progressClass = $achievement >= 100 ? 'bg-green-500' : ($achievement >= 70 ? 'bg-yellow-500' : 'bg-red-500');
                            @endphp
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4">
                                    <div class="text-sm font-medium text-gray-900">{{ $report->kpiTarget?->title ?? '-' }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm">
                                    {{ number_format($report->kpiTarget?->target_value ?? 0, 0, ',', '.') }} {{ $report->kpiTarget?->unit ?? '' }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap font-medium text-sm">
                                    {{ number_format($report->actual_value, 0, ',', '.') }}
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex items-center space-x-2">
                                        <div class="flex-1 bg-gray-200 rounded-full h-2 w-24">
                                            <div class="{{ $progressClass }} h-2 rounded-full" style="width: {{ min($achievement, 100) }}%"></div>
                                        </div>
                                        <span class="text-sm font-medium">{{ number_format($achievement, 0, ',', '.') }}%</span>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    {{ $report->report_date?->locale('id')->translatedFormat('d M Y') }}
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-500 max-w-xs truncate">
                                    {{ $report->notes ?: '-' }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    <a href="{{ route('kpi-reports.edit', $report) }}" class="text-blue-600 hover:text-blue-900">Edit</a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <div class="p-8 text-center text-gray-500">Belum ada laporan</div>
        @endif
    </div>
</div>
@endsection
