@extends('layouts.app')

@section('title', 'Detail Penilaian')

@section('content')
<div class="space-y-6">
    <!-- Page Header -->
    <div class="bg-white shadow-sm rounded-lg p-6">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Detail Penilaian Kinerja</h1>
                <p class="text-sm text-gray-600 mt-1">Hasil perhitungan Fuzzy Logic lengkap</p>
            </div>
            <div class="flex gap-2">
                <a href="{{ route('evaluations.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-600 text-white rounded-lg hover:bg-gray-700 transition-colors">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m7 7l7-7"/>
                    </svg>
                    Kembali
                </a>
                @can('update', $evaluation)
                    <a href="{{ route('evaluations.edit', $evaluation) }}" class="inline-flex items-center px-4 py-2 bg-yellow-600 text-white rounded-lg hover:bg-yellow-700 transition-colors">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2h2v-2H9l1.414-1.414L15 7.172V5h-1V4a1 1 0 00-1-1H4a1 1 0 00-1 1v4a1 1 0 001 1z"/>
                        </svg>
                        Edit
                    </a>
                @endcan
            </div>
        </div>
    </div>

    <!-- Employee Information -->
    <div class="bg-white shadow-sm rounded-lg overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200">
            <h3 class="text-lg font-semibold text-gray-900">Informasi Karyawan</h3>
        </div>
        <div class="p-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <p class="text-sm text-gray-600">Nama</p>
                    <p class="text-base font-medium text-gray-900">{{ $evaluation->employee->user->name }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-600">NIP</p>
                    <p class="text-base font-medium text-gray-900">{{ $evaluation->employee->employee_code }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-600">Jabatan</p>
                    <p class="text-base font-medium text-gray-900">{{ $evaluation->employee->position }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-600">Departemen</p>
                    <p class="text-base font-medium text-gray-900">{{ $evaluation->employee->department->name }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-600">Periode Penilaian</p>
                    <p class="text-base font-medium text-gray-900">{{ $evaluation->evaluation_period }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-600">Status</p>
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-800">
                        Aktif
                    </span>
                </div>
            </div>
        </div>
    </div>

    <!-- Fuzzy Logic Result -->
    <div class="bg-white shadow-sm rounded-lg overflow-hidden">
        <div class="px-6 py-4 bg-{{ $evaluation->performance_class === 'success' ? 'green' : ($evaluation->performance_class === 'primary' ? 'blue' : ($evaluation->performance_class === 'warning' ? 'yellow' : 'red')) }}-600 text-white">
            <h3 class="text-lg font-semibold">Hasil Perhitungan Fuzzy Logic</h3>
        </div>
        <div class="p-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 text-center">
                <div>
                    <h4 class="text-gray-700 mb-2">Skor Fuzzy</h4>
                    <div class="text-6xl font-bold text-{{ $evaluation->performance_class === 'success' ? 'green' : ($evaluation->performance_class === 'primary' ? 'blue' : ($evaluation->performance_class === 'warning' ? 'yellow' : 'red')) }}-600">
                        {{ $evaluation->fuzzy_score }}
                    </div>
                </div>
                <div>
                    <h4 class="text-gray-700 mb-2">Kategori Kinerja</h4>
                    <div class="inline-block">
                        <span class="inline-flex items-center px-6 py-3 rounded-full text-lg font-bold bg-{{ $evaluation->performance_class === 'success' ? 'green' : ($evaluation->performance_class === 'primary' ? 'blue' : ($evaluation->performance_class === 'warning' ? 'yellow' : 'red')) }}-100 text-{{ $evaluation->performance_class === 'success' ? 'green' : ($evaluation->performance_class === 'primary' ? 'blue' : ($evaluation->performance_class === 'warning' ? 'yellow' : 'red')) }}-800">
                            {{ $evaluation->category_label }}
                        </span>
                    </div>
                </div>
            </div>

            <div class="mt-6 p-4 rounded-lg bg-{{ $evaluation->performance_class === 'success' ? 'green' : ($evaluation->performance_class === 'primary' ? 'blue' : ($evaluation->performance_class === 'warning' ? 'yellow' : 'red')) }}-50 border border-{{ $evaluation->performance_class === 'success' ? 'green' : ($evaluation->performance_class === 'primary' ? 'blue' : ($evaluation->performance_class === 'warning' ? 'yellow' : 'red')) }}-200">
                <div class="flex items-start">
                    <svg class="w-6 h-6 text-{{ $evaluation->performance_class === 'success' ? 'green' : ($evaluation->performance_class === 'primary' ? 'blue' : ($evaluation->performance_class === 'warning' ? 'yellow' : 'red')) }}-600 mr-3" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 102 2v2a1 1 0 001 1h2a1 1 0 100-2V9a1 1 0 00-1-1z" clip-rule="evenodd"/>
                    </svg>
                    <div>
                        <h4 class="text-base font-semibold text-{{ $evaluation->performance_class === 'success' ? 'green' : ($evaluation->performance_class === 'primary' ? 'blue' : ($evaluation->performance_class === 'warning' ? 'yellow' : 'red')) }}-800">Rekomendasi HR</h4>
                        <p class="text-sm text-{{ $evaluation->performance_class === 'success' ? 'green' : ($evaluation->performance_class === 'primary' ? 'blue' : ($evaluation->performance_class === 'warning' ? 'yellow' : 'red')) }}-700 mt-1">{{ $evaluation->hr_recommendation }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Input Criteria Details -->
    <div class="bg-white shadow-sm rounded-lg overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200">
            <h3 class="text-lg font-semibold text-gray-900">Detail Kriteria Penilaian</h3>
        </div>
        <div class="p-6">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <!-- KPI Score -->
                <div class="text-center p-4 bg-gray-50 rounded-lg">
                    <h4 class="text-base font-semibold text-gray-700 mb-2">KPI Pencapaian</h4>
                    <div class="text-4xl font-bold text-blue-600 mb-1">{{ number_format($evaluation->kpi_score, 1) }}%</div>
                    <p class="text-sm text-gray-500">Target Penjualan</p>
                </div>

                <!-- Attendance -->
                <div class="text-center p-4 bg-gray-50 rounded-lg">
                    <h4 class="text-base font-semibold text-gray-700 mb-2">Tingkat Kehadiran</h4>
                    <div class="text-4xl font-bold text-green-600 mb-1">{{ number_format($evaluation->attendance_rate, 1) }}%</div>
                    <p class="text-sm text-gray-500">Kedisiplinan</p>
                </div>

                <!-- Customer Satisfaction -->
                <div class="text-center p-4 bg-gray-50 rounded-lg">
                    <h4 class="text-base font-semibold text-gray-700 mb-2">Kepuasan Pelanggan</h4>
                    <div class="text-4xl font-bold text-yellow-600 mb-1">{{ number_format($evaluation->customer_satisfaction, 1) }}</div>
                    <p class="text-sm text-gray-500">Kualitas Layanan</p>
                </div>
            </div>
        </div>
    </div>

    @if($evaluation->fuzzification_details)
    <!-- Fuzzification Process Details -->
    <div class="bg-white shadow-sm rounded-lg overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200">
            <h3 class="text-lg font-semibold text-gray-900">
                <svg class="w-5 h-5 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M15 7h6m0 10v-3m-3 3h.01M9 17h.01M15 7h6m0 10v-3m-3 3h.01M9 17h.01M15 7h6"/>
                </svg>
                Detail Perhitungan Fuzzy Logic
            </h3>
        </div>
        <div class="p-6">
            <!-- Fuzzification Results -->
            <div class="mb-6">
                <h4 class="text-md font-semibold text-gray-700 mb-4">1. Fuzzifikasi (Konversi Nilai Numerik ke Linguistik)</h4>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <!-- KPI Fuzzification -->
                    <div class="border border-gray-200 rounded-lg overflow-hidden">
                        <div class="bg-blue-600 text-white px-3 py-2">
                            <h5 class="text-sm font-semibold">KPI: {{ $evaluation->fuzzification_details['fuzzification_detail']['kpi']['input'] }}%</h5>
                        </div>
                        <div class="p-3 space-y-2">
                            @foreach($evaluation->fuzzification_details['fuzzification_detail']['kpi']['membership'] as $category => $value)
                                <div>
                                    <div class="flex justify-between items-center mb-1">
                                        <span class="text-xs text-gray-600">{{ ucfirst($category) }}</span>
                                        <span class="text-xs font-medium">{{ number_format($value * 100, 1) }}%</span>
                                    </div>
                                    <div class="w-full bg-gray-200 rounded-full h-2">
                                        <div class="bg-blue-500 h-2 rounded-full transition-all" style="width: {{ $value * 100 }}%"></div>
                                    </div>
                                </div>
                            @endforeach
                            @if($evaluation->fuzzification_details['fuzzification_detail']['kpi']['dominant']['value'] > 0)
                                <div class="mt-2 pt-2 border-t border-gray-200">
                                    <span class="text-xs font-medium">Dominan:</span>
                                    <span class="text-xs text-blue-600 font-semibold"> {{ ucfirst($evaluation->fuzzification_details['fuzzification_detail']['kpi']['dominant']['category']) }}</span>
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- Attendance Fuzzification -->
                    <div class="border border-gray-200 rounded-lg overflow-hidden">
                        <div class="bg-green-600 text-white px-3 py-2">
                            <h5 class="text-sm font-semibold">Kehadiran: {{ $evaluation->fuzzification_details['fuzzification_detail']['attendance']['input'] }}%</h5>
                        </div>
                        <div class="p-3 space-y-2">
                            @foreach($evaluation->fuzzification_details['fuzzification_detail']['attendance']['membership'] as $category => $value)
                                <div>
                                    <div class="flex justify-between items-center mb-1">
                                        <span class="text-xs text-gray-600">{{ ucfirst($category) }}</span>
                                        <span class="text-xs font-medium">{{ number_format($value * 100, 1) }}%</span>
                                    </div>
                                    <div class="w-full bg-gray-200 rounded-full h-2">
                                        <div class="bg-green-500 h-2 rounded-full transition-all" style="width: {{ $value * 100 }}%"></div>
                                    </div>
                                </div>
                            @endforeach
                            @if($evaluation->fuzzification_details['fuzzification_detail']['attendance']['dominant']['value'] > 0)
                                <div class="mt-2 pt-2 border-t border-gray-200">
                                    <span class="text-xs font-medium">Dominan:</span>
                                    <span class="text-xs text-green-600 font-semibold"> {{ ucfirst($evaluation->fuzzification_details['fuzzification_detail']['attendance']['dominant']['category']) }}</span>
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- Satisfaction Fuzzification -->
                    <div class="border border-gray-200 rounded-lg overflow-hidden">
                        <div class="bg-yellow-600 text-white px-3 py-2">
                            <h5 class="text-sm font-semibold">Kepuasan: {{ $evaluation->fuzzification_details['fuzzification_detail']['satisfaction']['input'] }}</h5>
                        </div>
                        <div class="p-3 space-y-2">
                            @foreach($evaluation->fuzzification_details['fuzzification_detail']['satisfaction']['membership'] as $category => $value)
                                <div>
                                    <div class="flex justify-between items-center mb-1">
                                        <span class="text-xs text-gray-600">{{ ucfirst($category) }}</span>
                                        <span class="text-xs font-medium">{{ number_format($value * 100, 1) }}%</span>
                                    </div>
                                    <div class="w-full bg-gray-200 rounded-full h-2">
                                        <div class="bg-yellow-500 h-2 rounded-full transition-all" style="width: {{ $value * 100 }}%"></div>
                                    </div>
                                </div>
                            @endforeach
                            @if($evaluation->fuzzification_details['fuzzification_detail']['satisfaction']['dominant']['value'] > 0)
                                <div class="mt-2 pt-2 border-t border-gray-200">
                                    <span class="text-xs font-medium">Dominan:</span>
                                    <span class="text-xs text-yellow-600 font-semibold"> {{ ucfirst($evaluation->fuzzification_details['fuzzification_detail']['satisfaction']['dominant']['category']) }}</span>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- Active Rules -->
            @if(isset($evaluation->fuzzification_details['active_rules']) && count($evaluation->fuzzification_details['active_rules']) > 0)
                <div class="mb-6">
                    <h4 class="text-md font-semibold text-gray-700 mb-4">2. Aturan Aktif (Rules yang digunakan)</h4>
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">No</th>
                                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Strength</th>
                                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Output (z)</th>
                                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Kontribusi</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach($evaluation->fuzzification_details['active_rules'] as $index => $rule)
                                    <tr>
                                        <td class="px-4 py-2 text-sm">{{ $index + 1 }}</td>
                                        <td class="px-4 py-2 text-sm">
                                            <span class="inline-flex items-center px-2 py-1 rounded text-xs font-medium bg-yellow-100 text-yellow-800">
                                                {{ number_format($rule['strength'] * 100, 1) }}%
                                            </span>
                                        </td>
                                        <td class="px-4 py-2 text-sm">{{ $rule['z'] }}</td>
                                        <td class="px-4 py-2 text-sm">{{ number_format($rule['strength'] * $rule['z'], 3) }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            @endif

            <!-- Defuzzification Process -->
            @if(isset($evaluation->fuzzification_details['defuzzification_process']))
                <div>
                    <h4 class="text-md font-semibold text-gray-700 mb-4">3. Defuzzifikasi (Perhitungan Skor Akhir)</h4>
                    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                        <div class="text-center p-4 bg-gray-50 rounded-lg">
                            <p class="text-xs text-gray-600 mb-1">Numerator (Σμ × z)</p>
                            <p class="text-lg font-semibold text-blue-600">{{ number_format($evaluation->fuzzification_details['defuzzification_process']['numerator'], 3) }}</p>
                        </div>
                        <div class="text-center p-4 bg-gray-50 rounded-lg">
                            <p class="text-xs text-gray-600 mb-1">Denominator (Σμ)</p>
                            <p class="text-lg font-semibold text-green-600">{{ number_format($evaluation->fuzzification_details['defuzzification_process']['denominator'], 3) }}</p>
                        </div>
                        <div class="text-center p-4 bg-gray-50 rounded-lg">
                            <p class="text-xs text-gray-600 mb-1">Rules Used</p>
                            <p class="text-lg font-semibold text-purple-600">{{ $evaluation->fuzzification_details['defuzzification_process']['rules_used'] }}</p>
                        </div>
                        <div class="text-center p-4 bg-gray-50 rounded-lg">
                            <p class="text-xs text-gray-600 mb-1">Hasil Akhir</p>
                            <p class="text-lg font-bold text-gray-900">{{ number_format($evaluation->fuzzification_details['defuzzification_process']['numerator'] / $evaluation->fuzzification_details['defuzzification_process']['denominator'], 2) }}</p>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>
    @endif

    <!-- Additional Information -->
    <div class="bg-white shadow-sm rounded-lg overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200">
            <h3 class="text-lg font-semibold text-gray-900">Informasi Tambahan</h3>
        </div>
        <div class="p-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <p class="text-sm text-gray-600">Penilai</p>
                    <p class="text-base font-medium text-gray-900">{{ $evaluation->evaluator->name }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-600">Tanggal Penilaian</p>
                    <p class="text-base font-medium text-gray-900">{{ $evaluation->created_at->format('d F Y H:i') }}</p>
                </div>
                @if($evaluation->updated_at != $evaluation->created_at)
                    <div class="md:col-span-2">
                        <p class="text-xs text-gray-500">Terakhir diperbarui: {{ $evaluation->updated_at->format('d F Y H:i') }}</p>
                    </div>
                @endif
                @if($evaluation->notes)
                    <div class="md:col-span-2">
                        <p class="text-sm text-gray-600">Catatan</p>
                        <p class="text-sm text-gray-900">{{ $evaluation->notes }}</p>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Action Buttons -->
    <div class="bg-white shadow-sm rounded-lg p-6">
        <div class="flex flex-wrap gap-3">
            <a href="{{ route('evaluations.index') }}" class="inline-flex items-center px-6 py-3 bg-gray-600 text-white rounded-lg hover:bg-gray-700 transition-colors">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16m-7 6h7"/>
                </svg>
                Lihat Semua Penilaian
            </a>
            <a href="{{ route('exports.evaluation.pdf', $evaluation) }}" class="inline-flex items-center px-6 py-3 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors" target="_blank">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
                Download PDF
            </a>
            @can('update', $evaluation)
                <a href="{{ route('evaluations.edit', $evaluation) }}" class="inline-flex items-center px-6 py-3 bg-yellow-600 text-white rounded-lg hover:bg-yellow-700 transition-colors">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2h2v-2H9l1.414-1.414L15 7.172V5h-1V4a1 1 0 00-1-1H4a1 1 0 00-1 1v4a1 1 0 001 1z"/>
                    </svg>
                    Edit Penilaian
                </a>
            @endcan
            <button onclick="window.print()" class="inline-flex items-center px-6 py-3 bg-gray-800 text-white rounded-lg hover:bg-gray-900 transition-colors">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H6a2 2 0 00-2-2V9a2 2 0 012-2h2a2 2 0 012 2v4a2 2 0 001 1z"/>
                </svg>
                Cetak Laporan
            </button>
        </div>
    </div>
</div>
@endsection