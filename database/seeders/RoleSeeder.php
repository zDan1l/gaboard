<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $roles = [
            [
                'name' => 'HR Manager',
                'slug' => 'hr_manager',
                'description' => 'HR dan Manager - Full access untuk manajemen karyawan dan penilaian',
            ],
            [
                'name' => 'Employee',
                'slug' => 'employee',
                'description' => 'Karyawan - Hanya dapat melihat hasil penilaian diri sendiri',
            ],
        ];

        foreach ($roles as $role) {
            Role::firstOrCreate(
                ['slug' => $role['slug']],
                $role
            );
        }
    }
}
