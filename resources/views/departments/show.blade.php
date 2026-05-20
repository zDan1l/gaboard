@extends('layouts.app')

@section('title', 'Detail Departemen')

@section('content')
<div class="space-y-6">
    <!-- Page Header -->
    <div class="bg-white shadow-sm rounded-lg p-6">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Detail Departemen</h1>
                <p class="text-sm text-gray-600 mt-1">Informasi lengkap departemen</p>
            </div>
            <div class="flex gap-2">
                <a href="{{ route('departments.edit', $department) }}" class="inline-flex items-center px-4 py-2 bg-yellow-600 text-white rounded-lg hover:bg-yellow-700 transition-colors">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2h2v-2H9l1.414-1.414L15 7.172V5h-1V4a1 1 0 00-1-1H4a1 1 0 00-1 1v4a1 1 0 001 1z"/>
                    </svg>
                    Edit
                </a>
                <a href="{{ route('departments.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-600 text-white rounded-lg hover:bg-gray-700 transition-colors">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m7 7l7-7"/>
                    </svg>
                    Kembali
                </a>
            </div>
        </div>
    </div>

    <!-- Department Information -->
    <div class="bg-white shadow-sm rounded-lg overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200">
            <h3 class="text-lg font-semibold text-gray-900">Informasi Departemen</h3>
        </div>
        <div class="p-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <p class="text-sm text-gray-600">Nama Departemen</p>
                    <p class="text-lg font-medium text-gray-900">{{ $department->name }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-600">Kode</p>
                    <p class="text-lg font-medium text-gray-900">{{ $department->code }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-600">Lokasi</p>
                    <p class="text-lg font-medium text-gray-900">{{ $department->location ?? 'N/A' }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-600">Jumlah Karyawan</p>
                    <p class="text-lg font-medium text-gray-900">{{ $department->employees_count ?? 0 }} orang</p>
                </div>
            </div>

            @if($department->description)
                <div class="mt-6">
                    <p class="text-sm text-gray-600">Deskripsi</p>
                    <p class="text-base text-gray-900 mt-1">{{ $department->description }}</p>
                </div>
            @endif
        </div>
    </div>

    <!-- Employees in Department -->
    @if($department->employees && $department->employees->count() > 0)
        <div class="bg-white shadow-sm rounded-lg overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-semibold text-gray-900">Karyawan di Departemen Ini</h3>
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Kode</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Posisi</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($department->employees as $employee)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-medium text-gray-900">{{ $employee->user->name }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900">{{ $employee->employee_code }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900">{{ $employee->position }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium {{ $employee->status === 'active' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                        {{ $employee->status }}
                                    </span>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    @endif
</div>
@endsection