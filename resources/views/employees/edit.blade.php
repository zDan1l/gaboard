@extends('layouts.app')

@section('title', 'Edit Karyawan')

@section('content')
<div class="space-y-6">
    <!-- Page Header -->
    <div class="bg-white shadow-sm rounded-lg p-6">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Edit Karyawan</h1>
                <p class="text-sm text-gray-600 mt-1">Update informasi karyawan</p>
            </div>
            <a href="{{ route('employees.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-600 text-white rounded-lg hover:bg-gray-700 transition-colors">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m7 7l7-7"/>
                </svg>
                Kembali
            </a>
        </div>
    </div>

    <!-- Form -->
    <div class="bg-white shadow-sm rounded-lg p-6">
        <form method="POST" action="{{ route('employees.update', $employee) }}" class="space-y-6">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="user_id" class="block text-sm font-medium text-gray-700 mb-2">User</label>
                    <select id="user_id" name="user_id" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-orange-500 @error('user_id') border-red-500 @enderror" required>
                        <option value="">Pilih User</option>
                        @foreach($users as $userOption)
                            <option value="{{ $userOption->id }}" {{ old('user_id', $employee->user_id) == $userOption->id ? 'selected' : '' }}>
                                {{ $userOption->name }} ({{ $userOption->email }})
                            </option>
                        @endforeach
                    </select>
                    @error('user_id')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="department_id" class="block text-sm font-medium text-gray-700 mb-2">Departemen</label>
                    <select id="department_id" name="department_id" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-orange-500 @error('department_id') border-red-500 @enderror" required>
                        <option value="">Pilih Departemen</option>
                        @foreach($departments as $department)
                            <option value="{{ $department->id }}" {{ old('department_id', $employee->department_id) == $department->id ? 'selected' : '' }}>
                                {{ $department->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('department_id')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="manager_id" class="block text-sm font-medium text-gray-700 mb-2">Manager</label>
                    <select id="manager_id" name="manager_id" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-orange-500 @error('manager_id') border-red-500 @enderror">
                        <option value="">Pilih Manager</option>
                        @foreach($managers as $manager)
                            <option value="{{ $manager->id }}" {{ old('manager_id', $employee->manager_id) == $manager->id ? 'selected' : '' }}>
                                {{ $manager->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('manager_id')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="position" class="block text-sm font-medium text-gray-700 mb-2">Posisi</label>
                    <input type="text" id="position" name="position" value="{{ old('position', $employee->position) }}" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-orange-500 @error('position') border-red-500 @enderror" required>
                    @error('position')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="employee_code" class="block text-sm font-medium text-gray-700 mb-2">Kode Karyawan</label>
                    <input type="text" id="employee_code" name="employee_code" value="{{ old('employee_code', $employee->employee_code) }}" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-orange-500 @error('employee_code') border-red-500 @enderror" required>
                    @error('employee_code')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="phone" class="block text-sm font-medium text-gray-700 mb-2">Telepon</label>
                    <input type="text" id="phone" name="phone" value="{{ old('phone', $employee->phone) }}" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-orange-500 @error('phone') border-red-500 @enderror">
                    @error('phone')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="join_date" class="block text-sm font-medium text-gray-700 mb-2">Tanggal Bergabung</label>
                    <input type="date" id="join_date" name="join_date" value="{{ old('join_date', $employee->join_date) }}" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-orange-500 @error('join_date') border-red-500 @enderror" required>
                    @error('join_date')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="status" class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                    <select id="status" name="status" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-orange-500 @error('status') border-red-500 @enderror" required>
                        <option value="active" {{ old('status', $employee->status) === 'active' ? 'selected' : '' }}>Aktif</option>
                        <option value="inactive" {{ old('status', $employee->status) === 'inactive' ? 'selected' : '' }}>Non-Aktif</option>
                        <option value="terminated" {{ old('status', $employee->status) === 'terminated' ? 'selected' : '' }}>Berhenti</option>
                    </select>
                    @error('status')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="flex items-center justify-between pt-6 border-t border-gray-200">
                <a href="{{ route('employees.index') }}" class="inline-flex items-center px-6 py-3 bg-gray-600 text-white rounded-lg hover:bg-gray-700 transition-colors">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                    Batal
                </a>
                <button type="submit" class="inline-flex items-center px-6 py-3 bg-orange-600 text-white rounded-lg hover:bg-orange-700 transition-colors">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                    </svg>
                    Update
                </button>
            </div>
        </form>
    </div>
</div>
@endsection