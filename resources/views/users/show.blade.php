@extends('layouts.app')

@section('title', 'Detail User')

@section('content')
<div class="space-y-6">
    <!-- Page Header -->
    <div class="bg-white shadow-sm rounded-lg p-6">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Detail User</h1>
                <p class="text-sm text-gray-600 mt-1">Informasi akun pengguna</p>
            </div>
            <div class="flex gap-2">
                <a href="{{ route('users.edit', $user) }}" class="inline-flex items-center px-4 py-2 bg-yellow-600 text-white rounded-lg hover:bg-yellow-700 transition-colors">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2h2v-2H9l1.414-1.414L15 7.172V5h-1V4a1 1 0 00-1-1H4a1 1 0 00-1 1v4a1 1 0 001 1z"/>
                    </svg>
                    Edit
                </a>
                <a href="{{ route('users.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-600 text-white rounded-lg hover:bg-gray-700 transition-colors">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m7 7l7-7"/>
                    </svg>
                    Kembali
                </a>
            </div>
        </div>
    </div>

    <!-- User Information -->
    <div class="bg-white shadow-sm rounded-lg overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200">
            <h3 class="text-lg font-semibold text-gray-900">Informasi Akun</h3>
        </div>
        <div class="p-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <p class="text-sm text-gray-600">Nama</p>
                    <p class="text-lg font-medium text-gray-900">{{ $user->name }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-600">Email</p>
                    <p class="text-lg font-medium text-gray-900">{{ $user->email }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-600">Role</p>
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-blue-100 text-blue-800">
                        {{ $user->role->name }}
                    </span>
                </div>
                <div>
                    <p class="text-sm text-gray-600">Tanggal Bergabung</p>
                    <p class="text-lg font-medium text-gray-900">{{ $user->created_at->format('d F Y') }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Employee Information -->
    @if($user->employee)
        <div class="bg-white shadow-sm rounded-lg overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-semibold text-gray-900">Data Karyawan</h3>
            </div>
            <div class="p-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <p class="text-sm text-gray-600">Kode Karyawan</p>
                        <p class="text-lg font-medium text-gray-900">{{ $user->employee->employee_code }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600">Posisi</p>
                        <p class="text-lg font-medium text-gray-900">{{ $user->employee->position }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600">Departemen</p>
                        <p class="text-lg font-medium text-gray-900">{{ $user->employee->department->name ?? 'N/A' }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600">Status</p>
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium {{ $user->employee->status === 'active' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                            {{ $user->employee->status }}
                        </span>
                    </div>
                </div>

                <div class="mt-6">
                    <a href="{{ route('employees.show', $user->employee) }}" class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                        </svg>
                        Lihat Profil Karyawan Lengkap
                    </a>
                </div>
            </div>
        </div>
    @else
        <div class="bg-white shadow-sm rounded-lg p-6">
            <div class="text-center text-gray-500">
                <svg class="mx-auto h-12 w-12 text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"/>
                </svg>
                <p class="text-lg font-medium">User ini belum terhubung dengan data karyawan</p>
                <p class="text-sm mt-2">Hubungkan user dengan karyawan di menu Karyawan</p>
            </div>
        </div>
    @endif
</div>
@endsection