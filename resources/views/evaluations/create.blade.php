@extends('layouts.app')

@section('title', 'Buat Penilaian Baru')

@section('content')
<div class="space-y-6">
    <!-- Page Header -->
    <div class="bg-white shadow-sm rounded-lg p-6">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Buat Penilaian Kinerja Baru</h1>
                <p class="text-sm text-gray-600 mt-1">Input kriteria penilaian karyawan dengan Fuzzy Logic</p>
            </div>
            <a href="{{ route('evaluations.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-600 text-white rounded-lg hover:bg-gray-700 transition-colors">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m7 7l7-7"/>
                </svg>
                Kembali
            </a>
        </div>
    </div>

    <!-- Form Input -->
    <div class="bg-white shadow-sm rounded-lg p-6">
        <div class="mb-6">
            <h3 class="text-lg font-semibold text-gray-900">Form Input Penilaian</h3>
            <p class="text-sm text-gray-600">Sistem akan menghitung skor kinerja secara otomatis menggunakan Fuzzy Logic</p>
        </div>

        <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 mb-6">
            <div class="flex">
                <svg class="w-5 h-5 text-blue-400 mr-3" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2V9a1 1 0 00-1-1z" clip-rule="evenodd"/>
                </svg>
                <div>
                    <h4 class="text-sm font-semibold text-blue-800">Metode Fuzzy Logic Sugeno</h4>
                    <p class="text-sm text-blue-600 mt-1">Sistem akan menghitung skor kinerja secara otomatis berdasarkan tiga input kriteria.</p>
                </div>
            </div>
        </div>

        <form action="{{ route('evaluations.store') }}" method="POST" class="space-y-6">
            @csrf

            <!-- Employee Selection -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="employee_id" class="block text-sm font-medium text-gray-700 mb-2">
                        Karyawan <span class="text-red-500">*</span>
                    </label>
                    <select name="employee_id" id="employee_id" required
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-orange-500">
                        <option value="">-- Pilih Karyawan --</option>
                        @foreach($employees as $employee)
                            <option value="{{ $employee->id }}">
                                {{ $employee->user->name }} - {{ $employee->position }}
                                ({{ $employee->department->name }})
                            </option>
                        @endforeach
                    </select>
                    @error('employee_id')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="evaluation_period" class="block text-sm font-medium text-gray-700 mb-2">
                        Periode Penilaian <span class="text-red-500">*</span>
                    </label>
                    <select name="evaluation_period" id="evaluation_period" required
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-orange-500">
                        <option value="">-- Pilih Periode --</option>
                        @foreach($periods as $period)
                            <option value="{{ $period }}">{{ $period }}</option>
                        @endforeach
                    </select>
                    @error('evaluation_period')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Evaluation Criteria -->
            <div class="flex items-center justify-between mb-4">
                <h4 class="text-md font-semibold text-gray-900">Kriteria Penilaian</h4>
                <button type="button" onclick="autoCalculateScores()"
                        class="inline-flex items-center px-3 py-2 bg-green-600 text-white text-sm rounded-lg hover:bg-green-700 transition-colors">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                    </svg>
                    Auto-Calculate (Data Real)
                </button>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <!-- KPI Score -->
                <div>
                    <label for="kpi_score" class="block text-sm font-medium text-gray-700 mb-2">
                        KPI Pencapaian (0-100%) <span class="text-red-500">*</span>
                    </label>
                    <input type="number" name="kpi_score" id="kpi_score"
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-orange-500"
                           min="0" max="100" step="0.01" required
                           placeholder="Contoh: 85.5">
                    @error('kpi_score')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                    <p class="mt-1 text-xs text-gray-500">Persentase pencapaian target penjualan</p>
                </div>

                <!-- Attendance Rate -->
                <div>
                    <label for="attendance_rate" class="block text-sm font-medium text-gray-700 mb-2">
                        Tingkat Kehadiran (0-100%) <span class="text-red-500">*</span>
                    </label>
                    <input type="number" name="attendance_rate" id="attendance_rate"
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-orange-500"
                           min="0" max="100" step="0.01" required
                           placeholder="Contoh: 95.0">
                    @error('attendance_rate')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                    <p class="mt-1 text-xs text-gray-500">Persentase kehadiran dari total hari kerja</p>
                </div>

                <!-- Customer Satisfaction -->
                <div>
                    <label for="customer_satisfaction" class="block text-sm font-medium text-gray-700 mb-2">
                        Kepuasan Pelanggan (1-10) <span class="text-red-500">*</span>
                    </label>
                    <input type="number" name="customer_satisfaction" id="customer_satisfaction"
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-orange-500"
                           min="1" max="10" step="0.1" required
                           placeholder="Contoh: 8.5">
                    @error('customer_satisfaction')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                    <p class="mt-1 text-xs text-gray-500">Rata-rata survei kepuasan pelanggan</p>
                </div>
            </div>

            <!-- Notes -->
            <div>
                <label for="notes" class="block text-sm font-medium text-gray-700 mb-2">
                    Catatan Tambahan
                </label>
                <textarea name="notes" id="notes" rows="3"
                          class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-orange-500"
                          placeholder="Masukkan catatan atau observasi tambahan...">{{ old('notes') }}</textarea>
                @error('notes')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Submit Buttons -->
            <div class="flex items-center justify-between pt-6 border-t border-gray-200">
                <a href="{{ route('evaluations.index') }}" class="inline-flex items-center px-6 py-3 bg-gray-600 text-white rounded-lg hover:bg-gray-700 transition-colors">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                    Batal
                </a>
                <button type="submit" class="inline-flex items-center px-6 py-3 bg-orange-600 text-white rounded-lg hover:bg-orange-700 transition-colors">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                    </svg>
                    Simpan & Hitung Skor
                </button>
            </div>
        </form>
    </div>

    <!-- Guide -->
    <div class="bg-white shadow-sm rounded-lg p-6">
        <h3 class="text-lg font-semibold text-gray-900 mb-4">Panduan Skor Fuzzy Logic</h3>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Performance Categories -->
            <div>
                <h4 class="text-md font-semibold text-gray-700 mb-3">Kategori Kinerja</h4>
                <div class="space-y-2">
                    @foreach([
                        ['0.85 - 1.00', 'Sangat Baik', 'success'],
                        ['0.65 - 0.84', 'Baik', 'primary'],
                        ['0.40 - 0.64', 'Cukup', 'warning'],
                        ['0.20 - 0.39', 'Buruk', 'danger'],
                        ['0.00 - 0.19', 'Sangat Buruk', 'dark'],
                    ] as $range)
                        <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                            <div class="flex-1">
                                <div class="text-sm font-medium text-gray-900">{{ $range[0] }}</div>
                                <div class="text-xs text-gray-500">{{ $range[1] }}</div>
                            </div>
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-{{ $range[2] }}-100 text-{{ $range[2] }}-700">
                                {{ $range[1] }}
                            </span>
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- Input Categories -->
            <div>
                <h4 class="text-md font-semibold text-gray-700 mb-3">Kategori Input</h4>
                <div class="space-y-2 text-sm">
                    <div class="flex justify-between py-2 border-b border-gray-200">
                        <span class="font-medium">KPI (%)</span>
                        <div class="flex gap-4 text-xs">
                            <span>Rendah: 0-60</span>
                            <span>Sedang: 50-85</span>
                            <span class="font-semibold text-green-600">Tinggi: 78-100</span>
                        </div>
                    </div>
                    <div class="flex justify-between py-2 border-b border-gray-200">
                        <span class="font-medium">Kehadiran (%)</span>
                        <div class="flex gap-4 text-xs">
                            <span>Rendah: 0-80</span>
                            <span>Sedang: 75-95</span>
                            <span class="font-semibold text-green-600">Tinggi: 90-100</span>
                        </div>
                    </div>
                    <div class="flex justify-between py-2">
                        <span class="font-medium">Kepuasan (1-10)</span>
                        <div class="flex gap-4 text-xs">
                            <span>Rendah: 1-5.5</span>
                            <span>Sedang: 4.5-8</span>
                            <span class="font-semibold text-green-600">Tinggi: 7-10</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
function autoCalculateScores() {
    const employeeId = document.getElementById('employee_id').value;
    const period = document.getElementById('evaluation_period').value;

    if (!employeeId) {
        alert('Pilih karyawan terlebih dahulu!');
        return;
    }

    // Show loading state
    const btn = event.target;
    const originalText = btn.innerHTML;
    btn.innerHTML = '<svg class="animate-spin h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg> Loading...';
    btn.disabled = true;

    fetch('{{ route('evaluations.auto-calculate') }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify({
            employee_id: employeeId,
            period: period || null
        })
    })
    .then(response => response.json())
    .then(data => {
        document.getElementById('kpi_score').value = data.kpi_score.toFixed(2);
        document.getElementById('attendance_rate').value = data.attendance_rate.toFixed(2);
        document.getElementById('customer_satisfaction').value = data.customer_satisfaction.toFixed(1);

        // Show details
        let details = 'Nilai berhasil dihitung dari data real:\n\n';
        details += `KPI: ${data.kpi_score.toFixed(2)}%\n`;
        if (data.details.kpi.has_targets) {
            details += `  - ${data.details.kpi.target_count} target(s)\n`;
            details += `  - ${data.details.kpi.reports_submitted} report(s) submitted\n`;
            if (data.details.kpi.reports_submitted < data.details.kpi.target_count) {
                details += `  - ${data.details.kpi.target_count - data.details.kpi.reports_submitted} tanpa report = 100% (default)\n`;
            }
        } else {
            details += `  - Tidak ada target KPI = 100% (default)\n`;
        }

        details += `\nKehadiran: ${data.attendance_rate.toFixed(2)}%\n`;
        if (data.details.attendance.has_data) {
            details += `  - ${data.details.attendance.present} hadir dari ${data.details.attendance.total_working_days} hari kerja\n`;
        } else {
            details += `  - Tidak ada data absensi = 100% (default)\n`;
        }

        details += `\nKepuasan Pelanggan: ${data.customer_satisfaction.toFixed(1)}/10\n`;
        if (data.details.satisfaction.has_scores) {
            details += `  - ${data.details.satisfaction.score_count} penilaian\n`;
            details += `  - Rata-rata: ${data.details.satisfaction.average_score.toFixed(1)}\n`;
        } else {
            details += `  - Tidak ada penilaian = 10/10 (default)\n`;
        }

        alert(details);
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Gagal menghitung nilai otomatis. Silakan coba lagi.');
    })
    .finally(() => {
        btn.innerHTML = originalText;
        btn.disabled = false;
    });
}
</script>
@endpush
@endsection