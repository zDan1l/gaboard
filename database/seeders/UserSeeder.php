<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $hrManagerRole = Role::where('slug', 'hr_manager')->first();
        $employeeRole = Role::where('slug', 'employee')->first();

        // HR/Manager Users (Full access) - 6 users untuk berbagai departemen
        $hrManagers = [
            ['name' => 'Budi Santoso', 'email' => 'hr.manager@company.com'],
            ['name' => 'Siti Rahayu', 'email' => 'manager.jkt@company.com'],
            ['name' => 'Ahmad Fauzi', 'email' => 'manager.sby@company.com'],
            ['name' => 'Dedi Kurniawan', 'email' => 'manager.bdg@company.com'],
            ['name' => 'Rina Wati', 'email' => 'manager.hq@company.com'],
            ['name' => 'Joko Susilo', 'email' => 'manager.ops@company.com'],
        ];

        foreach ($hrManagers as $hrManager) {
            User::firstOrCreate(
                ['email' => $hrManager['email']],
                [
                    'name' => $hrManager['name'],
                    'password' => Hash::make('password'),
                    'role_id' => $hrManagerRole->id,
                    'email_verified_at' => now(),
                ]
            );
        }

        // Employee Users (Karyawan biasa) - 20 users
        $employees = [
            ['name' => 'Aulia Putri', 'email' => 'aulia.putri@company.com'],
            ['name' => 'Bambang Sutrisno', 'email' => 'bambang.sutrisno@company.com'],
            ['name' => 'Citra Dewi', 'email' => 'citra.dewi@company.com'],
            ['name' => 'Dimas Prasetyo', 'email' => 'dimas.prasetyo@company.com'],
            ['name' => 'Eka Saputra', 'email' => 'eka.saputra@company.com'],
            ['name' => 'Fani Rahmawati', 'email' => 'fani.rahmawati@company.com'],
            ['name' => 'Gilang Ramadhan', 'email' => 'gilang.ramadhan@company.com'],
            ['name' => 'Hani Pertiwi', 'email' => 'hani.pertiwi@company.com'],
            ['name' => 'Indra Wijaya', 'email' => 'indra.wijaya@company.com'],
            ['name' => 'Jihan Nurul', 'email' => 'jihan.nurul@company.com'],
            ['name' => 'Kartika Sari', 'email' => 'kartika.sari@company.com'],
            ['name' => 'Lukman Hakim', 'email' => 'lukman.hakim@company.com'],
            ['name' => 'Maya Sari', 'email' => 'maya.sari@company.com'],
            ['name' => 'Nanda Pratama', 'email' => 'nanda.pratama@company.com'],
            ['name' => 'Oscar Kusuma', 'email' => 'oscar.kusuma@company.com'],
            ['name' => 'Putri Ayu', 'email' => 'putri.ayu@company.com'],
            ['name' => 'Rizky Hidayat', 'email' => 'rizky.hidayat@company.com'],
            ['name' => 'Siska Amalia', 'email' => 'siska.amalia@company.com'],
            ['name' => 'Taufik Hidayat', 'email' => 'taufik.hidayat@company.com'],
            ['name' => 'Utami Maharani', 'email' => 'utami.maharani@company.com'],
        ];

        foreach ($employees as $employee) {
            User::firstOrCreate(
                ['email' => $employee['email']],
                [
                    'name' => $employee['name'],
                    'password' => Hash::make('password'),
                    'role_id' => $employeeRole->id,
                    'email_verified_at' => now(),
                ]
            );
        }
    }
}
