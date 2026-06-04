@extends('layouts.app')

@section('title', 'Edit Target KPI - GaBoard')

@section('content')
<div class="space-y-6">
    <!-- Page Header -->
    <div class="bg-white shadow-sm rounded-lg p-6">
        <div class="flex items-center gap-4">
            <a href="{{ route('kpi-targets.index') }}" class="text-gray-400 hover:text-gray-600">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                </svg>
            </a>
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Edit Target KPI</h1>
                <p class="text-sm text-gray-600 mt-1">Ubah target KPI untuk {{ $kpiTarget->employee->user->name ?? '-' }}</p>
            </div>
        </div>
    </div>

    <!-- Form -->
    <div class="bg-white shadow-sm rounded-lg overflow-hidden">
        <form method="POST" action="{{ route('kpi-targets.update', $kpiTarget) }}" class="p-6 space-y-6">
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
                        <option value="{{ $employee->id }}" {{ old('employee_id', $kpiTarget->employee_id) == $employee->id ? 'selected' : '' }}>
                            {{ $employee->user->name ?? '-' }} - {{ $employee->position ?? '-' }}
                        </option>
                    @endforeach
                </select>
                @error('employee_id')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Title -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Judul Target *</label>
                <input type="text" name="title" required value="{{ old('title', $kpiTarget->title) }}" placeholder="Contoh: Target Penjualan Harian" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-orange-500 focus:border-orange-500">
                @error('title')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Description -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Deskripsi</label>
                <textarea name="description" rows="3" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-orange-500 focus:border-orange-500" placeholder="Jelaskan detail target KPI...">{{ old('description', $kpiTarget->description) }}</textarea>
                @error('description')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Target Value -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Nilai Target *</label>
                    <input type="number" name="target_value" required step="0.01" min="0" value="{{ old('target_value', $kpiTarget->target_value) }}" placeholder="100" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-orange-500 focus:border-orange-500">
                    @error('target_value')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Unit -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Satuan</label>
                    <input type="text" name="unit" value="{{ old('unit', $kpiTarget->unit) }}" placeholder="penjualan, jam, dll" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-orange-500 focus:border-orange-500">
                    @error('unit')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Period -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Periode *</label>
                    <select name="period" required class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-orange-500 focus:border-orange-500">
                        <option value="daily" {{ old('period', $kpiTarget->period) === 'daily' ? 'selected' : '' }}>Harian</option>
                        <option value="weekly" {{ old('period', $kpiTarget->period) === 'weekly' ? 'selected' : '' }}>Mingguan</option>
                        <option value="monthly" {{ old('period', $kpiTarget->period) === 'monthly' ? 'selected' : '' }}>Bulanan</option>
                        <option value="custom" {{ old('period', $kpiTarget->period) === 'custom' ? 'selected' : '' }}>Custom</option>
                    </select>
                    @error('period')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Status -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                    <select name="status" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-orange-500 focus:border-orange-500">
                        <option value="active" {{ old('status', $kpiTarget->status) === 'active' ? 'selected' : '' }}>Active</option>
                        <option value="inactive" {{ old('status', $kpiTarget->status) === 'inactive' ? 'selected' : '' }}>Inactive</option>
                    </select>
                    @error('status')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Start Date -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Tanggal Mulai *</label>
                    <input type="date" name="start_date" required value="{{ old('start_date', $kpiTarget->start_date?->format('Y-m-d')) }}" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-orange-500 focus:border-orange-500">
                    @error('start_date')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- End Date -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Tanggal Selesai</label>
                    <input type="date" name="end_date" value="{{ old('end_date', $kpiTarget->end_date?->format('Y-m-d')) }}" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-orange-500 focus:border-orange-500">
                    @error('end_date')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Current Progress Info -->
            @if($kpiTarget->reports && $kpiTarget->reports->count() > 0)
                <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                    <h4 class="font-medium text-blue-900 mb-2">Laporan Terakhir</h4>
                    <div class="grid grid-cols-2 gap-4 text-sm">
                        <div>
                            <span class="text-blue-700">Capaian:</span>
                            <span class="font-medium text-blue-900">{{ number_format($kpiTarget->reports->first()->actual_value, 0, ',', '.') }} {{ $kpiTarget->unit }}</span>
                        </div>
                        <div>
                            <span class="text-blue-700">Persentase:</span>
                            <span class="font-medium text-blue-900">{{ number_format($kpiTarget->achievement_percentage, 0, ',', '.') }}%</span>
                        </div>
                        <div class="col-span-2">
                            <span class="text-blue-700">Tanggal:</span>
                            <span class="text-blue-900">{{ $kpiTarget->reports->first()->report_date?->locale('id')->translatedFormat('d M Y') }}</span>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Actions -->
            <div class="flex justify-end space-x-3 pt-6 border-t border-gray-200">
                <a href="{{ route('kpi-targets.index') }}" class="px-6 py-2 border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors">
                    Batal
                </a>
                <button type="submit" class="px-6 py-2 bg-orange-600 text-white rounded-lg hover:bg-orange-700 transition-colors">
                    Update Target KPI
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
