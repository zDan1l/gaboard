<?php

namespace Database\Seeders;

use App\Models\Employee;
use App\Models\Evaluation;
use App\Services\FuzzyLogicService;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class EvaluationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $fuzzyService = new FuzzyLogicService();
        $employees = Employee::with('user')->where('status', 'active')->get();

        // Get HR Manager user as evaluator
        $hrManagerUser = User::whereHas('role', function($query) {
            $query->where('slug', 'hr_manager');
        })->first();

        if (!$hrManagerUser) {
            $this->command->warn('No HR Manager user found. Skipping evaluation seeding.');
            return;
        }

        $periods = ['Q1 2026', 'Q2 2026', 'Q3 2026', 'Q4 2025', 'Q1 2025'];
        $notes = [
            'Performa sangat baik, konsisten dalam mencapai target',
            'Perlu peningkatan di area kehadiran',
            'Kualitas kerja baik, namun perlu lebih inisiatif',
            'Sangat puas dengan pelanggan, pertahankan performa',
            'Perlu training tambahan untuk peningkatan skill',
            'Target tercapai dengan baik',
            'Kehadiran perlu diperhatikan',
            'Kinerja memuaskan, pertahankan kualitas ini',
            null,
            null,
        ];

        foreach ($employees as $employee) {
            // Create 1-3 evaluations per employee
            $numEvaluations = rand(1, 3);

            for ($i = 0; $i < $numEvaluations; $i++) {
                $period = $periods[array_rand($periods)];

                // Check if evaluation already exists for this period
                $existing = Evaluation::where('employee_id', $employee->id)
                    ->where('evaluation_period', $period)
                    ->first();

                if (!$existing) {
                    // Generate random performance data
                    $kpiScore = rand(45, 98);
                    $attendanceRate = rand(70, 100);
                    $customerSatisfaction = rand(4, 10) / 1.0;

                    // Calculate fuzzy score
                    $result = $fuzzyService->calculatePerformance(
                        $kpiScore,
                        $attendanceRate,
                        $customerSatisfaction
                    );

                    Evaluation::create([
                        'employee_id' => $employee->id,
                        'evaluator_id' => $hrManagerUser->id,
                        'evaluation_period' => $period,
                        'kpi_score' => $kpiScore,
                        'attendance_rate' => $attendanceRate,
                        'customer_satisfaction' => $customerSatisfaction,
                        'fuzzy_score' => $result['fuzzy_score'],
                        'category' => $result['category'],
                        'hr_recommendation' => $result['hr_recommendation'],
                        'fuzzification_details' => $result,
                        'notes' => $notes[array_rand($notes)],
                        'created_at' => now()->subDays(rand(10, 200)),
                        'updated_at' => now()->subDays(rand(1, 50)),
                    ]);
                }
            }
        }
    }
}
