@extends('layouts.app')

@section('title', 'Daftar Penilaian')

@section('content')
<div class="space-y-6">
    <!-- Page Header -->
    <div class="bg-white shadow-sm rounded-lg p-6">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Daftar Penilaian Kinerja</h1>
                <p class="text-sm text-gray-600 mt-1">Kelola dan monitoring penilaian kinerja karyawan</p>
            </div>
            <div class="flex flex-wrap gap-2">
                @if(auth()->user()->role->slug === 'hr_manager')
                <button onclick="showBatchGenerateModal()" class="inline-flex items-center px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                    </svg>
                    Generate Semua
                </button>
                <a href="{{ route('evaluations.create') }}" class="inline-flex items-center px-4 py-2 bg-orange-600 text-white rounded-lg hover:bg-orange-700 transition-colors">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                    </svg>
                    Buat Penilaian Baru
                </a>
                @endif
            </div>
        </div>
    </div>

    @if(auth()->user()->role->slug === 'hr_manager')
        <!-- Info Card for HR Manager -->
        <div class="bg-blue-50 border border-blue-200 rounded-lg p-6 mb-6">
            <div class="flex items-start space-x-4">
                <div class="flex-shrink-0">
                    <div class="w-12 h-12 bg-blue-500 rounded-full flex items-center justify-center">
                        <svg class="w-6 h-6 text-white" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2V9a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                        </svg>
                    </div>
                </div>
                <div class="flex-1">
                    <h3 class="text-lg font-semibold text-blue-900 mb-2">Cara Membuat Penilaian</h3>
                    <div class="space-y-2 text-sm text-blue-800">
                        <p><strong>Metode 1 - Otomatis (Disarankan):</strong></p>
                        <ol class="list-decimal list-inside space-y-1 ml-4">
                            <li>Pastikan karyawan sudah memiliki data: <span class="font-semibold">Target KPI → Laporan KPI</span>, <span class="font-semibold">Jadwal Absensi → Data Kehadiran</span>, dan <span class="font-semibold">Skor Kepuasan Pelanggan</span></li>
                            <li>Klik <span class="font-semibold">"Generate Semua"</span> untuk membuat penilaian semua karyawan sekaligus, atau</li>
                            <li>Klik <span class="font-semibold">"Buat Penilaian Baru"</span> → Pilih karyawan → Klik <span class="font-semibold">"Auto-Calculate"</span> → Simpan</li>
                        </ol>
                        <p class="mt-2"><strong>Metode 2 - Manual:</strong></p>
                        <ol class="list-decimal list-inside space-y-1 ml-4">
                            <li>Klik "Buat Penilaian Baru" → Pilih karyawan</li>
                            <li>Input manual nilai KPI, Kehadiran, dan Kepuasan Pelanggan</li>
                            <li>Klik "Simpan & Hitung Skor"</li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>

        <!-- Data Availability Status -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
            <div class="bg-white rounded-lg shadow p-4">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600">Total Karyawan</p>
                        <p class="text-2xl font-bold text-gray-900 mt-1">{{ App\Models\Employee::where('status', 'active')->count() }}</p>
                    </div>
                    <div class="h-10 w-10 bg-gray-100 rounded-full flex items-center justify-center">
                        <svg class="h-5 w-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                        </svg>
                    </div>
                </div>
            </div>
            <div class="bg-white rounded-lg shadow p-4">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600">Sudah Dinilai</p>
                        <p class="text-2xl font-bold text-green-600 mt-1">{{ $evaluations->count() }}</p>
                    </div>
                    <div class="h-10 w-10 bg-green-100 rounded-full flex items-center justify-center">
                        <svg class="h-5 w-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                </div>
            </div>
            <div class="bg-white rounded-lg shadow p-4">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600">Belum Dinilai</p>
                        <p class="text-2xl font-bold text-orange-600 mt-1">{{ App\Models\Employee::where('status', 'active')->count() - $evaluations->count() }}</p>
                    </div>
                    <div class="h-10 w-10 bg-orange-100 rounded-full flex items-center justify-center">
                        <svg class="h-5 w-5 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                </div>
            </div>
            <div class="bg-white rounded-lg shadow p-4">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600">Rata-rata Skor</p>
                        <p class="text-2xl font-bold text-blue-600 mt-1">{{ $evaluations->avg('fuzzy_score') ? number_format($evaluations->avg('fuzzy_score'), 2) : '-' }}</p>
                    </div>
                    <div class="h-10 w-10 bg-blue-100 rounded-full flex items-center justify-center">
                        <svg class="h-5 w-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                        </svg>
                    </div>
                </div>
            </div>
        </div>
    @endif

    @if(session('success'))
        <div class="bg-green-50 border border-green-200 text-green-800 rounded-lg p-4 mb-4">
            <div class="flex">
                <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                </svg>
                <div>{{ session('success') }}</div>
            </div>
        </div>
    @endif

    @if($evaluations->count() > 0)
        <div class="bg-white shadow-sm rounded-lg overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-semibold text-gray-900">Riwayat Penilaian</h3>
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Karyawan</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Periode</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Skor Fuzzy</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Kategori</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Penilai</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tanggal</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($evaluations as $evaluation)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div class="flex-shrink-0 h-10 w-10 bg-{{ $evaluation->performance_class }}-100 rounded-full flex items-center justify-center">
                                            <span class="text-{{ $evaluation->performance_class }}-600 font-medium text-sm">{{ substr($evaluation->employee->user->name, 0, 1) }}</span>
                                        </div>
                                        <div class="ml-4">
                                            <div class="text-sm font-medium text-gray-900">{{ $evaluation->employee->user->name }}</div>
                                            <div class="text-sm text-gray-500">{{ $evaluation->employee->position }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $evaluation->evaluation_period }}</td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-{{ $evaluation->performance_class }}-100 text-{{ $evaluation->performance_class }}-800">
                                        {{ $evaluation->fuzzy_score }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-{{ $evaluation->performance_class }}-100 text-{{ $evaluation->performance_class }}-800">
                                        {{ $evaluation->category_label }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $evaluation->evaluator->name }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $evaluation->created_at->format('d/m/Y') }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium space-x-2">
                                    <a href="{{ route('evaluations.show', $evaluation) }}" class="text-blue-600 hover:text-blue-900" title="Lihat Detail">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                        </svg>
                                    </a>
                                    <a href="{{ route('exports.evaluation.pdf', $evaluation) }}" class="text-green-600 hover:text-green-900" title="Export PDF" target="_blank">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                        </svg>
                                    </a>
                                    @if(auth()->user()->role->slug === 'hr_manager')
                                        <a href="{{ route('evaluations.edit', $evaluation) }}" class="text-yellow-600 hover:text-yellow-900" title="Edit">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2h2v-2H9l1.414-1.414L15 7.172V5h-1V4a1 1 0 00-1-1H4a1 1 0 00-1 1v4a1 1 0 001 1z"/>
                                            </svg>
                                        </a>
                                    @endif
                                    @if(auth()->user()->role->slug === 'hr_manager')
                                        <form action="{{ route('evaluations.destroy', $evaluation) }}" method="POST" class="inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus penilaian ini?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-600 hover:text-red-900" title="Hapus">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 011-1h4a1 1 0 011 1v3M4 7h16"/>
                                                </svg>
                                            </button>
                                        </form>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            @if($evaluations->hasPages())
                <div class="px-6 py-4 border-t border-gray-200">
                    {{ $evaluations->links() }}
                </div>
            @endif
        </div>
    @else
        <div class="bg-white shadow-sm rounded-lg p-12 text-center">
            <div class="max-w-md mx-auto">
                <svg class="mx-auto h-12 w-12 text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/>
                </svg>
                <h3 class="text-lg font-medium text-gray-900 mb-2">Belum Ada Data Penilaian</h3>
                <p class="text-gray-600 mb-6">Mulai dengan membuat penilaian kinerja untuk melihat data di sini.</p>
                @if(auth()->user()->role->slug === 'hr_manager')
                    <a href="{{ route('evaluations.create') }}" class="inline-flex items-center px-6 py-3 bg-orange-600 text-white rounded-lg hover:bg-orange-700 transition-colors">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                        </svg>
                        Buat Penilaian Pertama
                    </a>
                @endif
            </div>
        </div>
    @endif
</div>

<!-- Batch Generate Modal -->
@if(auth()->user()->role->slug === 'hr_manager')
<div id="batchGenerateModal" class="fixed inset-0 bg-gray-900 bg-opacity-50 hidden items-center justify-center z-50">
    <div class="bg-white rounded-lg shadow-xl p-6 w-full max-w-lg mx-4 max-h-[90vh] overflow-y-auto">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-lg font-semibold text-gray-900">Generate Penilaian Semua Karyawan</h3>
            <button onclick="closeBatchGenerateModal()" class="text-gray-400 hover:text-gray-600">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>

        <div class="mb-4">
            <p class="text-sm text-gray-600 mb-4">
                Sistem akan membuat penilaian untuk semua karyawan aktif secara otomatis menggunakan data real yang tersedia.
            </p>

            <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4 mb-4">
                <div class="flex">
                    <svg class="w-5 h-5 text-yellow-400 mr-3 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                    </svg>
                    <div class="text-sm text-yellow-800">
                        <p class="font-semibold">Catatan Penting:</p>
                        <ul class="list-disc list-inside mt-1 space-y-1">
                            <li>Penilaian hanya akan dibuat untuk karyawan yang <strong>belum memiliki penilaian</strong> pada periode terpilih</li>
                            <li><strong>Periode yang bertumpang tindih (overlap) akan ditolak</strong>. Contoh: jika ada penilaian 1-30 Juni, periode 15 Juni-15 Juli akan ditolak karena overlap di tanggal 15-30 Juni</li>
                            <li>Karyawan akan <strong>dilewati (SKIP)</strong> jika data tidak lengkap: Tidak ada target KPI, tidak ada data absensi, atau tidak ada survei kepuasan</li>
                            <li>Penilaian dihitung berdasarkan data yang <strong>tersedia</strong> (misal: 5 dari 10 KPI targets = hanya hitung 5 yang ada)</li>
                            <li>Setiap karyawan hanya akan memiliki 1 penilaian per periode yang tidak tumpang tindih</li>
                        </ul>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Tanggal Mulai</label>
                    <input type="date" id="batchStartDate" class="w-full border border-gray-300 rounded-lg px-3 py-2" required>
                    <p class="text-xs text-gray-500 mt-1">Format: YYYY-MM-DD</p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Tanggal Akhir</label>
                    <input type="date" id="batchEndDate" class="w-full border border-gray-300 rounded-lg px-3 py-2" required>
                    <p class="text-xs text-gray-500 mt-1">Format: YYYY-MM-DD (maksimal 1 tahun)</p>
                </div>
            </div>

            <div id="batchProgress" class="hidden mb-4">
                <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                    <div class="flex items-center space-x-2">
                        <svg class="animate-spin h-5 w-5 text-blue-600" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                        <span class="text-sm text-blue-800" id="batchProgressText">Memproses...</span>
                    </div>
                </div>
            </div>
        </div>

        <div class="flex justify-end space-x-3">
            <button onclick="closeBatchGenerateModal()" class="px-4 py-2 border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors">
                Batal
            </button>
            <button onclick="startBatchGenerate()" class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors">
                Generate Sekarang
            </button>
        </div>
    </div>
</div>
@endif
@endsection

@push('scripts')
<script>
function showBatchGenerateModal() {
    var modal = document.getElementById('batchGenerateModal');
    modal.classList.remove('hidden');
    modal.classList.add('flex');
}

function closeBatchGenerateModal() {
    var modal = document.getElementById('batchGenerateModal');
    modal.classList.add('hidden');
    modal.classList.remove('flex');
    document.getElementById('batchProgress').classList.add('hidden');
}

async function startBatchGenerate() {
    var startDate = document.getElementById('batchStartDate').value;
    var endDate = document.getElementById('batchEndDate').value;

    if (!startDate || !endDate) {
        alert('Harap pilih tanggal mulai dan tanggal akhir');
        return;
    }

    document.getElementById('batchProgress').classList.remove('hidden');
    document.getElementById('batchProgressText').textContent = 'Memproses data karyawan...';

    try {
        var response = await fetch('/evaluations/batch-generate', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({
                start_date: startDate,
                end_date: endDate
            })
        });

        var result = await response.json();

        if (result.success) {
            var message = 'Selesai! ' + result.created + ' penilaian berhasil dibuat.';

            if (result.skipped_exists > 0) {
                message += ' ' + result.skipped_exists + ' dilewati (sudah ada).';
            }

            if (result.skipped_no_data && result.skipped_no_data.length > 0) {
                message += ' ' + result.skipped_no_data.length + ' dilewati (data tidak lengkap).';
            }

            if (result.errors && result.errors.length > 0) {
                message += ' ' + result.errors.length + ' error.';
            }

            document.getElementById('batchProgressText').textContent = message;
            setTimeout(function() {
                window.location.href = '{{ route("evaluations.index") }}';
            }, 3000);
        } else {
            document.getElementById('batchProgressText').textContent = 'Error: ' + (result.message || 'Terjadi kesalahan');
        }
    } catch (error) {
        document.getElementById('batchProgressText').textContent = 'Error: Terjadi kesalahan koneksi';
        console.error('Error:', error);
    }
}
</script>
@endpush
