<?php

namespace Database\Seeders;

use App\Models\Department;
use App\Models\Employee;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class EmployeeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $departments = Department::all();

        // Get all users who should be employees (both HR Manager and Employee roles)
        $users = User::whereHas('role', function($query) {
            $query->whereIn('slug', ['employee', 'hr_manager']);
        })->get();

        // Get HR Manager users who can be managers
        $managerUsers = User::whereHas('role', function($query) {
            $query->where('slug', 'hr_manager');
        })->get();

        $positions = [
            'Staff Restoran', 'Kasir', 'Waiter', 'Cook', 'Barista',
            'Supervisor', 'Asisten Manager', 'Staff Administrasi', 'Quality Control',
            'Inventory Control', 'Marketing Staff', 'Customer Service'
        ];

        $employeeCodes = [
            'MGC001', 'MGC002', 'MGC003', 'MGC004', 'MGC005',
            'MGC006', 'MGC007', 'MGC008', 'MGC009', 'MGC010',
            'MGC011', 'MGC012', 'MGC013', 'MGC014', 'MGC015',
            'MGC016', 'MGC017', 'MGC018', 'MGC019', 'MGC020',
            'MGC021', 'MGC022', 'MGC023', 'MGC024', 'MGC025',
            'MGC026', 'MGC027', 'MGC028', 'MGC029', 'MGC030',
        ];

        foreach ($users as $index => $user) {
            // Assign department and manager
            if ($user->role->slug === 'hr_manager') {
                // HR Managers work in departments, some don't have managers
                $department = $departments->skip($index % $departments->count())->first();
                $managerId = null; // HR Managers don't have managers
            } else {
                // Regular employees report to HR Managers
                $department = $departments->random();
                $manager = $managerUsers->random();
                $managerId = Employee::where('user_id', $manager->id)->first()?->id;
            }

            // Check if employee already exists
            $existingEmployee = Employee::where('user_id', $user->id)->first();
            if (!$existingEmployee) {
                Employee::create([
                    'user_id' => $user->id,
                    'department_id' => $department->id,
                    'manager_id' => $managerId,
                    'employee_code' => $employeeCodes[$index] ?? 'MGC' . str_pad($index + 1, 3, '0', STR_PAD_LEFT),
                    'position' => $positions[array_rand($positions)],
                    'phone' => '08' . rand(11, 99) . rand(1000, 9999) . rand(1000, 9999),
                    'join_date' => now()->subDays(rand(100, 1000))->format('Y-m-d'),
                    'status' => 'active',
                ]);
            }
        }
    }
}
