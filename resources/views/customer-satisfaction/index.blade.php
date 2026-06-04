@extends('layouts.app')

@section('title', 'Nilai Kepuasan Pelanggan - GaBoard')

@section('content')
<div class="space-y-6">
    <!-- Page Header -->
    <div class="bg-white shadow-sm rounded-lg p-6">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Nilai Kepuasan Pelanggan</h1>
                <p class="text-sm text-gray-600 mt-1">Input dan kelola nilai kepuasan pelanggan untuk karyawan</p>
            </div>
            <a href="{{ route('customer-satisfaction.create') }}" class="inline-flex items-center px-4 py-2 bg-orange-600 text-white rounded-lg hover:bg-orange-700 transition-colors">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                </svg>
                Input Nilai Baru
            </a>
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

    <!-- Filters -->
    <form method="GET" action="{{ route('customer-satisfaction.index') }}" class="bg-white shadow-sm rounded-lg p-6">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Filter Karyawan</label>
                <select name="employee_id" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-orange-500 focus:border-orange-500">
                    <option value="">Semua Karyawan</option>
                    @foreach($employees as $employee)
                        <option value="{{ $employee->id }}" {{ request('employee_id') == $employee->id ? 'selected' : '' }}>
                            {{ $employee->user->name ?? '-' }} - {{ $employee->position ?? '' }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Filter Periode</label>
                <input type="month" name="period" value="{{ request('period') }}" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-orange-500 focus:border-orange-500">
            </div>
            <div class="flex items-end gap-2">
                <button type="submit" class="flex-1 bg-gray-800 text-white px-4 py-2 rounded-lg hover:bg-gray-900 transition-colors">
                    Terapkan Filter
                </button>
                <a href="{{ route('customer-satisfaction.index') }}" class="px-4 py-2 border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors">
                    Reset
                </a>
            </div>
        </div>
    </form>

    <!-- Score Cards -->
    @if($topPerformers->count() > 0)
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            @foreach($topPerformers as $item)
                @php
                    $avg = $item['average'];
                    $gradientClass = match(true) {
                        $avg >= 4 => 'from-green-400 to-green-500',
                        $avg >= 3 => 'from-yellow-400 to-yellow-500',
                        $avg >= 2 => 'from-orange-400 to-orange-500',
                        default => 'from-red-400 to-red-500',
                    };
                @endphp
                <div class="bg-gradient-to-br {{ $gradientClass }} rounded-xl shadow-lg p-6 text-white">
                    <div class="flex items-center space-x-3 mb-4">
                        <div class="bg-white/20 w-12 h-12 rounded-full flex items-center justify-center">
                            <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.828 14.828a4 4 0 01-5.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                        <div>
                            <p class="font-semibold">{{ $item['employee']->user->name ?? '-' }}</p>
                            <p class="text-sm opacity-80">{{ $item['employee']->position ?? '-' }}</p>
                        </div>
                    </div>
                    <div class="text-4xl font-bold mb-1">{{ number_format($avg, 1) }}</div>
                    <p class="text-sm opacity-80">dari {{ $item['count'] }} penilaian</p>
                </div>
            @endforeach
        </div>
    @endif

    <!-- Scores Table -->
    <div class="bg-white shadow-sm rounded-lg overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200">
            <h3 class="text-lg font-semibold text-gray-900">Daftar Penilaian ({{ $scores->count() }})</h3>
        </div>
        @if($scores->count() > 0)
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Karyawan</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nilai</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Label</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Periode</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Diberikan Oleh</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Catatan</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($scores as $score)
                            @php
                                $scoreClass = match(true) {
                                    $score->score >= 4 => 'bg-green-100 text-green-800',
                                    $score->score >= 3 => 'bg-yellow-100 text-yellow-800',
                                    $score->score >= 2 => 'bg-orange-100 text-orange-800',
                                    default => 'bg-red-100 text-red-800',
                                };
                                $starCount = (int) round($score->score);
                                $stars = str_repeat('★', $starCount) . str_repeat('☆', 5 - $starCount);
                            @endphp
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4">
                                    <div class="flex items-center">
                                        <div class="flex-shrink-0 h-10 w-10 bg-orange-100 rounded-full flex items-center justify-center">
                                            <span class="text-orange-600 font-medium text-sm">{{ $score->employee->user->name[0] ?? '-' }}</span>
                                        </div>
                                        <div class="ml-4">
                                            <div class="text-sm font-medium text-gray-900">{{ $score->employee->user->name ?? '-' }}</div>
                                            <div class="text-sm text-gray-500">{{ $score->employee->position ?? '-' }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="text-xl">{{ $stars }}</span>
                                    <span class="ml-2 font-bold text-lg">{{ number_format($score->score, 1) }}</span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="px-2 py-1 text-xs font-medium rounded-full {{ $scoreClass }}">{{ $score->score_label }}</span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $score->period ?: '-' }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $score->ratedBy->name ?? '-' }}</td>
                                <td class="px-6 py-4 text-sm text-gray-500 max-w-xs truncate">{{ $score->notes ?: '-' }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium space-x-2">
                                    <a href="{{ route('customer-satisfaction.edit', $score) }}" class="text-blue-600 hover:text-blue-900">Edit</a>
                                    <form action="{{ route('customer-satisfaction.destroy', $score) }}" method="POST" class="inline" onsubmit="return confirm('Yakin ingin menghapus penilaian ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:text-red-900">Hapus</button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <div class="p-12 text-center">
                <div class="h-16 w-16 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                    <svg class="h-8 w-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"/>
                    </svg>
                </div>
                <h3 class="text-lg font-medium text-gray-900 mb-2">Belum ada data penilaian</h3>
                <p class="text-gray-600">Mulai dengan memberikan penilaian kepuasan pelanggan untuk karyawan.</p>
            </div>
        @endif
    </div>
</div>
@endsection
