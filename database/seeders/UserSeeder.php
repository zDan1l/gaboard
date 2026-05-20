<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
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
            ['name' => 'Budi Santoso', 'email' => 'hr.manager@gacoan.com'],
            ['name' => 'Siti Rahayu', 'email' => 'manager.jkt@gacoan.com'],
            ['name' => 'Ahmad Fauzi', 'email' => 'manager.sby@gacoan.com'],
            ['name' => 'Dedi Kurniawan', 'email' => 'manager.bdg@gacoan.com'],
            ['name' => 'Rina Wati', 'email' => 'manager.hq@gacoan.com'],
            ['name' => 'Joko Susilo', 'email' => 'manager.ops@gacoan.com'],
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
            ['name' => 'Aulia Putri', 'email' => 'aulia.putri@gacoan.com'],
            ['name' => 'Bambang Sutrisno', 'email' => 'bambang.sutrisno@gacoan.com'],
            ['name' => 'Citra Dewi', 'email' => 'citra.dewi@gacoan.com'],
            ['name' => 'Dimas Prasetyo', 'email' => 'dimas.prasetyo@gacoan.com'],
            ['name' => 'Eka Saputra', 'email' => 'eka.saputra@gacoan.com'],
            ['name' => 'Fani Rahmawati', 'email' => 'fani.rahmawati@gacoan.com'],
            ['name' => 'Gilang Ramadhan', 'email' => 'gilang.ramadhan@gacoan.com'],
            ['name' => 'Hani Pertiwi', 'email' => 'hani.pertiwi@gacoan.com'],
            ['name' => 'Indra Wijaya', 'email' => 'indra.wijaya@gacoan.com'],
            ['name' => 'Jihan Nurul', 'email' => 'jihan.nurul@gacoan.com'],
            ['name' => 'Kartika Sari', 'email' => 'kartika.sari@gacoan.com'],
            ['name' => 'Lukman Hakim', 'email' => 'lukman.hakim@gacoan.com'],
            ['name' => 'Maya Sari', 'email' => 'maya.sari@gacoan.com'],
            ['name' => 'Nanda Pratama', 'email' => 'nanda.pratama@gacoan.com'],
            ['name' => 'Oscar Kusuma', 'email' => 'oscar.kusuma@gacoan.com'],
            ['name' => 'Putri Ayu', 'email' => 'putri.ayu@gacoan.com'],
            ['name' => 'Rizky Hidayat', 'email' => 'rizky.hidayat@gacoan.com'],
            ['name' => 'Siska Amalia', 'email' => 'siska.amalia@gacoan.com'],
            ['name' => 'Taufik Hidayat', 'email' => 'taufik.hidayat@gacoan.com'],
            ['name' => 'Utami Maharani', 'email' => 'utami.maharani@gacoan.com'],
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
