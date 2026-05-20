<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $hrManagerRole = \App\Models\Role::where('slug', 'hr_manager')->first();
        $hqDepartment = \App\Models\Department::where('code', 'HQ')->first();

        if (!$hrManagerRole || !$hqDepartment) {
            $this->command->error('Please run RoleSeeder and DepartmentSeeder first!');
            return;
        }

        // Check if admin already exists
        $existingAdmin = User::where('email', 'hr.manager@gacoan.com')->first();
        if ($existingAdmin) {
            $this->command->info('Admin HR Manager already exists!');
            return;
        }

        $admin = User::create([
            'name' => 'Admin HR Manager',
            'email' => 'hr.manager@gacoan.com',
            'password' => Hash::make('password'), // Ganti dengan password yang aman
            'role_id' => $hrManagerRole->id,
            'email_verified_at' => now(),
        ]);

        // Create employee record for admin
        \App\Models\Employee::create([
            'user_id' => $admin->id,
            'department_id' => $hqDepartment->id,
            'employee_code' => 'MGC-ADMIN',
            'position' => 'HR Manager',
            'phone' => '08123456789',
            'join_date' => now(),
            'status' => 'active',
        ]);

        $this->command->info('Admin HR Manager user created successfully!');
        $this->command->info('Email: hr.manager@gacoan.com');
        $this->command->info('Password: password');
    }
}
