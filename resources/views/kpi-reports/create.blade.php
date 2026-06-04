@extends('layouts.app')

@section('title', 'Lapor Capaian KPI - GaBoard')

@section('content')
<div class="space-y-6">
    <!-- Page Header -->
    <div class="bg-white shadow-sm rounded-lg p-6">
        <div class="flex items-center gap-4">
            <a href="{{ route('kpi-reports.index') }}" class="text-gray-400 hover:text-gray-600">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                </svg>
            </a>
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Lapor Capaian Target</h1>
                <p class="text-sm text-gray-600 mt-1">Laporkan capaian KPI Anda</p>
            </div>
        </div>
    </div>

    <!-- Target Info Card -->
    <div class="bg-orange-50 border border-orange-200 rounded-lg p-6">
        <h3 class="font-semibold text-orange-900 mb-4">Target: {{ $kpiTarget->title }}</h3>
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4 text-sm">
            <div>
                <span class="text-orange-700">Nilai Target:</span>
                <span class="font-medium text-orange-900 ml-2">{{ number_format($kpiTarget->target_value, 0, ',', '.') }} {{ $kpiTarget->unit }}</span>
            </div>
            <div>
                <span class="text-orange-700">Periode:</span>
                <span class="font-medium text-orange-900 ml-2">
                    {{ match($kpiTarget->period) {
                        'daily' => 'Harian',
                        'weekly' => 'Mingguan',
                        'monthly' => 'Bulanan',
                        'custom' => 'Custom',
                        default => $kpiTarget->period,
                    } }}
                </span>
            </div>
            <div>
                <span class="text-orange-700">Tanggal Mulai:</span>
                <span class="font-medium text-orange-900 ml-2">{{ $kpiTarget->start_date?->locale('id')->translatedFormat('d M Y') }}</span>
            </div>
            @if($kpiTarget->end_date)
                <div>
                    <span class="text-orange-700">Tanggal Selesai:</span>
                    <span class="font-medium text-orange-900 ml-2">{{ $kpiTarget->end_date->locale('id')->translatedFormat('d M Y') }}</span>
                </div>
            @endif
        </div>
    </div>

    @if($kpiTarget->reports && $kpiTarget->reports->count() > 0)
        <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
            <h4 class="font-medium text-blue-900 mb-2">Laporan Terakhir</h4>
            <div class="grid grid-cols-2 gap-4 text-sm">
                <div>
                    <span class="text-blue-700">Capaian:</span>
                    <span class="font-medium text-blue-900 ml-2">{{ number_format($kpiTarget->reports->first()->actual_value, 0, ',', '.') }} {{ $kpiTarget->unit }}</span>
                </div>
                <div>
                    <span class="text-blue-700">Persentase:</span>
                    <span class="font-medium text-blue-900 ml-2">{{ number_format(($kpiTarget->reports->first()->actual_value / $kpiTarget->target_value) * 100, 0, ',', '.') }}%</span>
                </div>
                <div class="col-span-2">
                    <span class="text-blue-700">Tanggal:</span>
                    <span class="text-blue-900 ml-2">{{ $kpiTarget->reports->first()->report_date->locale('id')->translatedFormat('d M Y') }}</span>
                </div>
                @if($kpiTarget->reports->first()->notes)
                    <div class="col-span-2">
                        <span class="text-blue-700">Catatan:</span>
                        <span class="text-blue-900 ml-2">{{ $kpiTarget->reports->first()->notes }}</span>
                    </div>
                @endif
            </div>
        </div>
    @endif

    <!-- Form -->
    <div class="bg-white shadow-sm rounded-lg overflow-hidden">
        <form method="POST" action="{{ route('kpi-reports.store') }}" class="p-6 space-y-6" id="reportForm">
            @csrf
            <input type="hidden" name="kpi_target_id" value="{{ $kpiTarget->id }}">

            @error('actual_value')
                <div class="bg-red-50 border border-red-200 text-red-800 rounded-lg p-4">{{ $message }}</div>
            @enderror

            <!-- Actual Value -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Nilai Capaian *</label>
                <input type="number" name="actual_value" required step="0.01" min="0" id="actualValue" value="{{ old('actual_value') }}" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-orange-500 focus:border-orange-500" placeholder="Masukkan nilai capaian Anda...">
                <p class="text-sm text-gray-500 mt-1">Masukkan nilai dalam {{ $kpiTarget->unit ?: 'satuan yang sesuai' }}</p>
                @error('actual_value')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Achievement Preview -->
            <div class="bg-gray-50 rounded-lg p-4 text-center" id="achievementPreview" style="display: none;">
                <p class="text-sm text-gray-600">Pencapaian</p>
                <p id="achievementPercent" class="text-3xl font-bold text-orange-600">0%</p>
            </div>

            <!-- Report Date -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Tanggal Laporan *</label>
                <input type="date" name="report_date" required value="{{ old('report_date', now()->format('Y-m-d')) }}" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-orange-500 focus:border-orange-500">
                @error('report_date')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Notes -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Catatan</label>
                <textarea name="notes" rows="3" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-orange-500 focus:border-orange-500" placeholder="Tambahkan catatan jika diperlukan...">{{ old('notes') }}</textarea>
                @error('notes')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Actions -->
            <div class="flex justify-end space-x-3 pt-6 border-t border-gray-200">
                <a href="{{ route('kpi-reports.index') }}" class="px-6 py-2 border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors">
                    Batal
                </a>
                <button type="submit" class="px-6 py-2 bg-orange-600 text-white rounded-lg hover:bg-orange-700 transition-colors">
                    Kirim Laporan
                </button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
const targetValue = {{ $kpiTarget->target_value }};
const actualValueInput = document.getElementById('actualValue');
const achievementPreview = document.getElementById('achievementPreview');
const achievementPercent = document.getElementById('achievementPercent');

function updateAchievementPreview() {
    const actual = parseFloat(actualValueInput.value) || 0;
    if (actual > 0) {
        const achievement = ((actual / targetValue) * 100).toFixed(0);
        achievementPercent.textContent = achievement + '%';
        achievementPreview.style.display = 'block';
    } else {
        achievementPreview.style.display = 'none';
    }
}

actualValueInput.addEventListener('input', updateAchievementPreview);
</script>
@endpush
@endsection
