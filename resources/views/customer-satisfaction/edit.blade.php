@extends('layouts.app')

@section('title', 'Edit Nilai Kepuasan Pelanggan - GaBoard')

@section('content')
<div class="space-y-6">
    <!-- Page Header -->
    <div class="bg-white shadow-sm rounded-lg p-6">
        <div class="flex items-center gap-4">
            <a href="{{ route('customer-satisfaction.index') }}" class="text-gray-400 hover:text-gray-600">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                </svg>
            </a>
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Edit Nilai Kepuasan Pelanggan</h1>
                <p class="text-sm text-gray-600 mt-1">Perbarui penilaian untuk {{ $customerSatisfactionScore->employee->user->name ?? '-' }}</p>
            </div>
        </div>
    </div>

    <!-- Current Score Display -->
    <div class="bg-orange-50 border border-orange-200 rounded-lg p-6">
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
            <div>
                <span class="text-orange-700 text-sm">Karyawan:</span>
                <div class="font-medium text-orange-900">{{ $customerSatisfactionScore->employee->user->name ?? '-' }}</div>
            </div>
            <div>
                <span class="text-orange-700 text-sm">Jabatan:</span>
                <div class="font-medium text-orange-900">{{ $customerSatisfactionScore->employee->position ?? '-' }}</div>
            </div>
            <div>
                <span class="text-orange-700 text-sm">Nilai Saat Ini:</span>
                <div class="font-medium text-orange-900 text-lg">{{ number_format($customerSatisfactionScore->score, 1) }}</div>
            </div>
            <div>
                <span class="text-orange-700 text-sm">Label:</span>
                <div class="font-medium text-orange-900">{{ $customerSatisfactionScore->score_label }}</div>
            </div>
        </div>
    </div>

    <!-- Form -->
    <div class="bg-white shadow-sm rounded-lg overflow-hidden">
        <form method="POST" action="{{ route('customer-satisfaction.update', $customerSatisfactionScore) }}" class="p-6 space-y-6" id="scoreForm">
            @csrf
            @method('PUT')

            @error('employee_id')
                <div class="bg-red-50 border border-red-200 text-red-800 rounded-lg p-4">{{ $message }}</div>
            @enderror

            <!-- Employee Selection -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Karyawan *</label>
                <select name="employee_id" required class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-orange-500 focus:border-orange-500">
                    @foreach($employees as $employee)
                        <option value="{{ $employee->id }}" {{ old('employee_id', $customerSatisfactionScore->employee_id) == $employee->id ? 'selected' : '' }}>
                            {{ $employee->user->name ?? '-' }} - {{ $employee->position ?? '-' }}
                        </option>
                    @endforeach
                </select>
                @error('employee_id')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Score Input -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Nilai Kepuasan (1-5) *</label>
                <div class="flex items-center space-x-4">
                    <input type="range" name="score" id="scoreInput" min="1" max="5" step="0.1" value="{{ old('score', $customerSatisfactionScore->score) }}" class="flex-1 h-2 bg-gray-200 rounded-lg appearance-none cursor-pointer" oninput="updateScorePreview()">
                    <span id="scorePreview" class="text-2xl font-bold text-orange-600 w-12 text-center">{{ number_format(old('score', $customerSatisfactionScore->score), 1) }}</span>
                </div>
                <div class="flex justify-between text-xs text-gray-500 mt-1">
                    <span>Sangat Buruk (1)</span>
                    <span>Sangat Baik (5)</span>
                </div>
                @error('score')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Score Labels Reference -->
            <div class="bg-gray-50 rounded-lg p-4">
                <h4 class="text-sm font-medium text-gray-700 mb-2">Referensi Nilai:</h4>
                @php
                    $scoreRanges = [
                        ['min' => 1.0, 'max' => 1.4, 'label' => 'Sangat Buruk', 'bg' => 'bg-red-100 text-red-800'],
                        ['min' => 1.5, 'max' => 2.4, 'label' => 'Buruk', 'bg' => 'bg-orange-100 text-orange-800'],
                        ['min' => 2.5, 'max' => 3.4, 'label' => 'Cukup', 'bg' => 'bg-yellow-100 text-yellow-800'],
                        ['min' => 3.5, 'max' => 4.4, 'label' => 'Baik', 'bg' => 'bg-lime-100 text-lime-800'],
                        ['min' => 4.5, 'max' => 5.0, 'label' => 'Sangat Baik', 'bg' => 'bg-green-100 text-green-800'],
                    ];
                @endphp
                <div class="grid grid-cols-5 gap-2 text-center text-xs">
                    @foreach($scoreRanges as $range)
                        <div class="{{ $range['bg'] }} rounded p-2">
                            <div class="font-bold">{{ $range['min'] }}-{{ $range['max'] }}</div>
                            <div>{{ $range['label'] }}</div>
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- Period -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Periode</label>
                <input type="month" name="period" id="periodInput" value="{{ old('period', $customerSatisfactionScore->period) }}" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-orange-500 focus:border-orange-500">
                <p class="text-xs text-gray-500 mt-1">Kosongkan untuk periode saat ini</p>
                @error('period')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Notes -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Catatan</label>
                <textarea name="notes" rows="3" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-orange-500 focus:border-orange-500" placeholder="Tambahkan catatan atau alasan penilaian...">{{ old('notes', $customerSatisfactionScore->notes) }}</textarea>
                @error('notes')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Meta Info -->
            <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                <h4 class="text-sm font-medium text-blue-900 mb-2">Informasi Penilaian</h4>
                <div class="grid grid-cols-2 gap-4 text-sm">
                    <div>
                        <span class="text-blue-700">Diberikan Oleh:</span>
                        <span class="text-blue-900 ml-2">{{ $customerSatisfactionScore->ratedBy->name ?? '-' }}</span>
                    </div>
                    <div>
                        <span class="text-blue-700">Tanggal:</span>
                        <span class="text-blue-900 ml-2">{{ $customerSatisfactionScore->created_at?->locale('id')->translatedFormat('d M Y H:i') }}</span>
                    </div>
                </div>
            </div>

            <!-- Actions -->
            <div class="flex justify-end space-x-3 pt-6 border-t border-gray-200">
                <a href="{{ route('customer-satisfaction.index') }}" class="px-6 py-2 border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors">
                    Batal
                </a>
                <form action="{{ route('customer-satisfaction.destroy', $customerSatisfactionScore) }}" method="POST" class="inline" onsubmit="return confirm('Yakin ingin menghapus penilaian ini?')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="px-6 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-colors">
                        Hapus
                    </button>
                </form>
                <button type="submit" class="px-6 py-2 bg-orange-600 text-white rounded-lg hover:bg-orange-700 transition-colors">
                    Update Penilaian
                </button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
function updateScorePreview() {
    const score = parseFloat(document.getElementById('scoreInput').value);
    document.getElementById('scorePreview').textContent = score.toFixed(1);
}
</script>
@endpush
