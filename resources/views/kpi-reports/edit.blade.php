@extends('layouts.app')

@section('title', 'Edit Laporan KPI - GaBoard')

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
                <h1 class="text-2xl font-bold text-gray-900">Edit Laporan KPI</h1>
                <p class="text-sm text-gray-600 mt-1">Perbarui laporan capaian: {{ $kpiReport->kpiTarget?->title ?? '-' }}</p>
            </div>
        </div>
    </div>

    <!-- Target Info Card -->
    <div class="bg-orange-50 border border-orange-200 rounded-lg p-6">
        <h3 class="font-semibold text-orange-900 mb-4">Target: {{ $kpiReport->kpiTarget?->title ?? '-' }}</h3>
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4 text-sm">
            <div>
                <span class="text-orange-700">Nilai Target:</span>
                <span class="font-medium text-orange-900 ml-2">{{ number_format($kpiReport->kpiTarget?->target_value ?? 0, 0, ',', '.') }} {{ $kpiReport->kpiTarget?->unit ?? '' }}</span>
            </div>
            <div>
                <span class="text-orange-700">Periode:</span>
                <span class="font-medium text-orange-900 ml-2">
                    {{ match($kpiReport->kpiTarget?->period ?? '') {
                        'daily' => 'Harian',
                        'weekly' => 'Mingguan',
                        'monthly' => 'Bulanan',
                        'custom' => 'Custom',
                        default => $kpiReport->kpiTarget?->period,
                    } }}
                </span>
            </div>
            <div>
                <span class="text-orange-700">Capaian Saat Ini:</span>
                <span class="font-medium text-orange-900 ml-2">{{ number_format($kpiReport->actual_value, 0, ',', '.') }} {{ $kpiReport->kpiTarget?->unit ?? '' }}</span>
            </div>
            <div>
                <span class="text-orange-700">Persentase:</span>
                <span class="font-medium text-orange-900 ml-2">{{ number_format($kpiReport->achievement_percentage ?? 0, 0, ',', '.') }}%</span>
            </div>
        </div>
    </div>

    <!-- Form -->
    <div class="bg-white shadow-sm rounded-lg overflow-hidden">
        <form method="POST" action="{{ route('kpi-reports.update', $kpiReport) }}" class="p-6 space-y-6" id="reportForm">
            @csrf
            @method('PUT')

            @error('actual_value')
                <div class="bg-red-50 border border-red-200 text-red-800 rounded-lg p-4">{{ $message }}</div>
            @enderror

            <!-- Actual Value -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Nilai Capaian *</label>
                <input type="number" name="actual_value" required step="0.01" min="0" id="actualValue" value="{{ old('actual_value', $kpiReport->actual_value) }}" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-orange-500 focus:border-orange-500" placeholder="Masukkan nilai capaian Anda...">
                <p class="text-sm text-gray-500 mt-1">Masukkan nilai dalam {{ $kpiReport->kpiTarget?->unit ?: 'satuan yang sesuai' }}</p>
                @error('actual_value')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Achievement Preview -->
            <div class="bg-gray-50 rounded-lg p-4 text-center" id="achievementPreview">
                <p class="text-sm text-gray-600">Pencapaian</p>
                <p id="achievementPercent" class="text-3xl font-bold text-orange-600">0%</p>
            </div>

            <!-- Report Date -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Tanggal Laporan *</label>
                <input type="date" name="report_date" required value="{{ old('report_date', $kpiReport->report_date?->format('Y-m-d')) }}" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-orange-500 focus:border-orange-500">
                @error('report_date')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Notes -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Catatan</label>
                <textarea name="notes" rows="3" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-orange-500 focus:border-orange-500" placeholder="Tambahkan catatan jika diperlukan...">{{ old('notes', $kpiReport->notes) }}</textarea>
                @error('notes')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Actions -->
            <div class="flex justify-end space-x-3 pt-6 border-t border-gray-200">
                <a href="{{ route('kpi-reports.index') }}" class="px-6 py-2 border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors">
                    Batal
                </a>
                <form action="{{ route('kpi-reports.destroy', $kpiReport) }}" method="POST" class="inline" onsubmit="return confirm('Yakin ingin menghapus laporan ini?')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="px-6 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-colors">
                        Hapus
                    </button>
                </form>
                <button type="submit" class="px-6 py-2 bg-orange-600 text-white rounded-lg hover:bg-orange-700 transition-colors">
                    Update Laporan
                </button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
const targetValue = {{ $kpiReport->kpiTarget?->target_value ?? 0 }};
const actualValueInput = document.getElementById('actualValue');
const achievementPreview = document.getElementById('achievementPreview');
const achievementPercent = document.getElementById('achievementPercent');

function updateAchievementPreview() {
    const actual = parseFloat(actualValueInput.value) || 0;
    if (actual > 0 && targetValue > 0) {
        const achievement = ((actual / targetValue) * 100).toFixed(0);
        achievementPercent.textContent = achievement + '%';
    }
}

// Initial update
updateAchievementPreview();
actualValueInput.addEventListener('input', updateAchievementPreview);
</script>
@endpush
@endsection
