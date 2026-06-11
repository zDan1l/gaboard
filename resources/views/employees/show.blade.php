@extends('layouts.app')

@section('title', 'Detail Karyawan')

@section('content')
<div class="space-y-6">
    <!-- Page Header -->
    <div class="bg-white shadow-sm rounded-lg p-6">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Detail Karyawan</h1>
                <p class="text-sm text-gray-600 mt-1">Informasi lengkap karyawan</p>
            </div>
            <div class="flex gap-2">
                <a href="{{ route('employees.edit', $employee) }}" class="inline-flex items-center px-4 py-2 bg-yellow-600 text-white rounded-lg hover:bg-yellow-700 transition-colors">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2h2v-2H9l1.414-1.414L15 7.172V5h-1V4a1 1 0 00-1-1H4a1 1 0 00-1 1v4a1 1 0 001 1z"/>
                    </svg>
                    Edit
                </a>
                <form method="POST" action="{{ route('employees.destroy', $employee) }}" class="inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus karyawan ini? Tindakan ini tidak dapat dibatalkan.');">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="inline-flex items-center px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-colors">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                        </svg>
                        Hapus
                    </button>
                </form>
                <a href="{{ route('employees.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-600 text-white rounded-lg hover:bg-gray-700 transition-colors">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m7 7l7-7"/>
                    </svg>
                    Kembali
                </a>
            </div>
        </div>
    </div>

    <!-- Employee Information -->
    <div class="bg-white shadow-sm rounded-lg overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200">
            <h3 class="text-lg font-semibold text-gray-900">Informasi Pribadi</h3>
        </div>
        <div class="p-6">
            <div class="flex items-center mb-6">
                <div class="h-16 w-16 bg-orange-100 rounded-full flex items-center justify-center mr-4">
                    <span class="text-2xl font-bold text-orange-600">{{ substr($employee->user->name, 0, 1) }}</span>
                </div>
                <div>
                    <h4 class="text-xl font-bold text-gray-900">{{ $employee->user->name }}</h4>
                    <p class="text-sm text-gray-600">{{ $employee->position }}</p>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <p class="text-sm text-gray-600">Kode Karyawan</p>
                    <p class="text-lg font-medium text-gray-900">{{ $employee->employee_code }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-600">Email</p>
                    <p class="text-lg font-medium text-gray-900">{{ $employee->user->email }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-600">Departemen</p>
                    <p class="text-lg font-medium text-gray-900">{{ $employee->department->name ?? 'N/A' }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-600">Manager</p>
                    <p class="text-lg font-medium text-gray-900">{{ $employee->manager->name ?? 'N/A' }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-600">Telepon</p>
                    <p class="text-lg font-medium text-gray-900">{{ $employee->phone ?? 'N/A' }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-600">Status</p>
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium {{ $employee->status === 'active' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                        {{ $employee->status }}
                    </span>
                </div>
                <div>
                    <p class="text-sm text-gray-600">Tanggal Bergabung</p>
                    <p class="text-lg font-medium text-gray-900">{{ $employee->join_date ? $employee->join_date->format('d F Y') : 'N/A' }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-600">Role Akun</p>
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-blue-100 text-blue-800">
                        {{ $employee->user->role->name }}
                    </span>
                </div>
            </div>
        </div>
    </div>

    <!-- Employee Evaluations -->
    <div class="bg-white shadow-sm rounded-lg overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200">
            <h3 class="text-lg font-semibold text-gray-900">Riwayat Penilaian</h3>
        </div>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Periode</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Skor</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Kategori</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Penilai</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tanggal</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @if($employee->evaluations && $employee->evaluations->count() > 0)
                        @foreach($employee->evaluations as $evaluation)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $evaluation->evaluation_period }}</td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-{{ $evaluation->performance_class }}-100 text-{{ $evaluation->performance_class }}-800">
                                        {{ $evaluation->fuzzy_score ?? 'N/A' }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-{{ $evaluation->performance_class }}-100 text-{{ $evaluation->performance_class }}-800">
                                        {{ $evaluation->category_label }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $evaluation->evaluator->name }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $evaluation->created_at->format('d/m/Y') }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    <a href="{{ route('evaluations.show', $evaluation) }}" class="text-blue-600 hover:text-blue-900">Lihat</a>
                                </td>
                            </tr>
                        @endforeach
                    @else
                        <tr>
                            <td colspan="6" class="px-6 py-4 text-center text-sm text-gray-500">
                                Belum ada penilaian untuk karyawan ini
                            </td>
                        </tr>
                    @endif
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection