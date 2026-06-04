<?php

namespace Database\Seeders;

use App\Models\AttendanceSchedule;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class AttendanceScheduleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get HR Manager user (first one) as created_by
        $hrManager = User::whereHas('role', function ($query) {
            $query->where('slug', 'hr_manager');
        })->first();

        if (! $hrManager) {
            return;
        }

        $schedules = [];

        // Create schedules for the next 30 days
        for ($i = 0; $i < 30; $i++) {
            $date = Carbon::today()->addDays($i);
            $dayOfWeek = $date->dayOfWeek; // 0 (Sunday) to 6 (Saturday)

            // Sunday (0) is off, other days are working days
            $isWorkingDay = $dayOfWeek !== 0;

            $title = $isWorkingDay ? 'Hari Kerja' : 'Hari Libur (Minggu)';

            $schedules[] = [
                'schedule_date' => $date->format('Y-m-d'),
                'title' => $title,
                'description' => $isWorkingDay
                    ? 'Jadwal absensi harian untuk semua karyawan'
                    : 'Hari libur mingguan',
                'is_working_day' => $isWorkingDay,
                'created_by' => $hrManager->id,
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }

        AttendanceSchedule::insert($schedules);
    }
}
