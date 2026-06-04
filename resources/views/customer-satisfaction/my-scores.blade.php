@extends('layouts.app')

@section('title', 'Nilai Kepuasan Saya - GaBoard')

@section('content')
<div class="space-y-6">
    <!-- Page Header -->
    <div class="bg-white shadow-sm rounded-lg p-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Nilai Kepuasan Pelanggan Saya</h1>
            <p class="text-sm text-gray-600 mt-1">Lihat nilai kepuasan pelanggan yang diberikan manager</p>
        </div>
    </div>

    <!-- Average Score Card -->
    <div class="bg-gradient-to-r from-orange-500 to-orange-600 rounded-xl shadow-lg p-8 text-white text-center">
        <p class="text-orange-100 mb-2">Rata-rata Nilai Kepuasan</p>
        <p id="averageScore" class="text-6xl font-bold">-</p>
        <div id="starsDisplay" class="text-4xl mt-4">☆☆☆☆☆</div>
    </div>

    <!-- Score History -->
    <div class="bg-white shadow-sm rounded-lg overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200">
            <h3 class="text-lg font-semibold text-gray-900">Riwayat Penilaian</h3>
        </div>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Periode</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nilai</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Label</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Diberikan Oleh</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Catatan</th>
                    </tr>
                </thead>
                <tbody id="scoresTable" class="bg-white divide-y divide-gray-200">
                    <tr>
                        <td colspan="5" class="px-6 py-4 text-center text-gray-500">Memuat data...</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Empty State -->
    <div id="emptyState" class="bg-white shadow-sm rounded-lg p-12 text-center hidden">
        <div class="h-16 w-16 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
            <svg class="h-8 w-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"/>
            </svg>
        </div>
        <h3 class="text-lg font-medium text-gray-900 mb-2">Belum Ada Penilaian</h3>
        <p class="text-gray-600">Anda belum menerima penilaian kepuasan pelanggan. Hubungi manager atau HR untuk informasi lebih lanjut.</p>
    </div>
</div>
@endsection

@push('scripts')
<script>
let myScores = [];

async function loadMyScores() {
    try {
        const response = await fetch('/customer-satisfaction/my-scores-data');
        myScores = await response.json();
        renderTable();
        updateAverage();
        checkEmpty();
    } catch (error) {
        console.error('Error loading scores:', error);
    }
}

function renderTable() {
    const tbody = document.getElementById('scoresTable');

    if (myScores.length === 0) {
        tbody.innerHTML = '';
        return;
    }

    tbody.innerHTML = myScores.map(score => {
        const scoreClass = score.score >= 4 ? 'bg-green-100 text-green-800' :
            score.score >= 3 ? 'bg-yellow-100 text-yellow-800' :
            score.score >= 2 ? 'bg-orange-100 text-orange-800' :
            'bg-red-100 text-red-800';

        const stars = '★'.repeat(Math.round(score.score)) + '☆'.repeat(5 - Math.round(score.score));

        return `
            <tr class="hover:bg-gray-50">
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">${score.period || '-'}</td>
                <td class="px-6 py-4 whitespace-nowrap">
                    <span class="text-xl">${stars}</span>
                    <span class="ml-2 font-bold text-lg">${score.score}</span>
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                    <span class="px-2 py-1 text-xs font-medium rounded-full ${scoreClass}">${score.score_label || '-'}</span>
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">${score.rated_by?.name || '-'}</td>
                <td class="px-6 py-4 text-sm text-gray-500 max-w-xs truncate">${score.notes || '-'}</td>
            </tr>
        `;
    }).join('');
}

function updateAverage() {
    if (myScores.length === 0) {
        document.getElementById('averageScore').textContent = '-';
        return;
    }

    const sum = myScores.reduce((acc, score) => acc + parseFloat(score.score), 0);
    const avg = (sum / myScores.length).toFixed(1);

    document.getElementById('averageScore').textContent = avg;

    const stars = '★'.repeat(Math.round(avg)) + '☆'.repeat(5 - Math.round(avg));
    document.getElementById('starsDisplay').textContent = stars;
}

function checkEmpty() {
    const emptyState = document.getElementById('emptyState');
    const tableContainer = document.querySelector('.overflow-x-auto').parentElement;

    if (myScores.length === 0) {
        emptyState.classList.remove('hidden');
        tableContainer.classList.add('hidden');
    } else {
        emptyState.classList.add('hidden');
        tableContainer.classList.remove('hidden');
    }
}

document.addEventListener('DOMContentLoaded', () => {
    loadMyScores();
});
</script>
@endpush
