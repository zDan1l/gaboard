@extends('layouts.app')

@section('title', 'Tambah Karyawan')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="mb-4">
                <h2>Tambah Karyawan Baru</h2>
                <a href="{{ route('employees.index') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Kembali
                </a>
            </div>

            <div class="card">
                <div class="card-body">
                    <form method="POST" action="{{ route('employees.store') }}">
                        @csrf

                        <div class="mb-3">
                            <label for="user_id" class="form-label">User</label>
                            <select class="form-select @error('user_id') is-invalid @enderror"
                                    id="user_id"
                                    name="user_id"
                                    required>
                                <option value="">Pilih User</option>
                                @foreach($users as $user)
                                    <option value="{{ $user->id }}"
                                            {{ old('user_id') == $user->id ? 'selected' : '' }}>
                                        {{ $user->name }} ({{ $user->email }})
                                    </option>
                                @endforeach
                            </select>
                            @error('user_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="department_id" class="form-label">Departemen</label>
                            <select class="form-select @error('department_id') is-invalid @enderror"
                                    id="department_id"
                                    name="department_id"
                                    required>
                                <option value="">Pilih Departemen</option>
                                @foreach($departments as $department)
                                    <option value="{{ $department->id }}"
                                            {{ old('department_id') == $department->id ? 'selected' : '' }}>
                                        {{ $department->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('department_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="manager_id" class="form-label">Manager</label>
                            <select class="form-select @error('manager_id') is-invalid @enderror"
                                    id="manager_id"
                                    name="manager_id">
                                <option value="">Pilih Manager</option>
                                @foreach($managers as $manager)
                                    <option value="{{ $manager->id }}"
                                            {{ old('manager_id') == $manager->id ? 'selected' : '' }}>
                                        {{ $manager->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('manager_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="employee_code" class="form-label">Kode Karyawan</label>
                            <input type="text"
                                   class="form-control @error('employee_code') is-invalid @enderror"
                                   id="employee_code"
                                   name="employee_code"
                                   value="{{ old('employee_code') }}"
                                   required>
                            @error('employee_code')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="position" class="form-label">Posisi</label>
                            <input type="text"
                                   class="form-control @error('position') is-invalid @enderror"
                                   id="position"
                                   name="position"
                                   value="{{ old('position') }}"
                                   required>
                            @error('position')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="phone" class="form-label">Telepon</label>
                            <input type="text"
                                   class="form-control @error('phone') is-invalid @enderror"
                                   id="phone"
                                   name="phone"
                                   value="{{ old('phone') }}">
                            @error('phone')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="join_date" class="form-label">Tanggal Bergabung</label>
                            <input type="date"
                                   class="form-control @error('join_date') is-invalid @enderror"
                                   id="join_date"
                                   name="join_date"
                                   value="{{ old('join_date') }}"
                                   required>
                            @error('join_date')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="status" class="form-label">Status</label>
                            <select class="form-select @error('status') is-invalid @enderror"
                                    id="status"
                                    name="status"
                                    required>
                                <option value="active" {{ old('status', 'active') === 'active' ? 'selected' : '' }}>Active</option>
                                <option value="inactive" {{ old('status') === 'inactive' ? 'selected' : '' }}>Inactive</option>
                                <option value="terminated" {{ old('status') === 'terminated' ? 'selected' : '' }}>Terminated</option>
                            </select>
                            @error('status')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i> Simpan
                            </button>
                            <a href="{{ route('employees.index') }}" class="btn btn-secondary">
                                <i class="fas fa-times"></i> Batal
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection