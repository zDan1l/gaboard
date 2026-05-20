<?php

namespace Database\Seeders;

use App\Models\Department;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DepartmentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $departments = [
            [
                'name' => 'Kantor Pusat',
                'code' => 'HQ',
                'location' => 'Jakarta Selatan',
                'description' => 'Kantor Pusat Mie Gacoan - HR & Management Office',
            ],
            [
                'name' => 'Gerai Jakarta Pusat',
                'code' => 'JKT-01',
                'location' => 'Jakarta Pusat',
                'description' => 'Gerai Mie Gacoan Jakarta Pusat',
            ],
            [
                'name' => 'Gerai Jakarta Selatan',
                'code' => 'JKT-02',
                'location' => 'Jakarta Selatan',
                'description' => 'Gerai Mie Gacoan Jakarta Selatan',
            ],
            [
                'name' => 'Gerai Jakarta Barat',
                'code' => 'JKT-03',
                'location' => 'Jakarta Barat',
                'description' => 'Gerai Mie Gacoan Jakarta Barat',
            ],
            [
                'name' => 'Gerai Surabaya',
                'code' => 'SBY-01',
                'location' => 'Surabaya Pusat',
                'description' => 'Gerai Mie Gacoan Surabaya',
            ],
            [
                'name' => 'Gerai Bandung',
                'code' => 'BDG-01',
                'location' => 'Bandung Pusat',
                'description' => 'Gerai Mie Gacoan Bandung',
            ],
        ];

        foreach ($departments as $department) {
            Department::firstOrCreate(
                ['code' => $department['code']],
                $department
            );
        }
    }
}
