<?php

namespace App\Console\Commands;

use App\Models\Evaluation;
use App\Models\Employee;
use App\Services\EvaluationCalculatorService;
use App\Services\FuzzyLogicService;
use Illuminate\Console\Command;

class UpdateEvaluations extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'evaluations:update {--delete-old : Delete existing evaluations and recreate}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update all evaluations with correct calculation from real data';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $calculator = new EvaluationCalculatorService();
        $fuzzyService = new FuzzyLogicService();

        if ($this->option('delete-old')) {
            $this->info('Deleting all existing evaluations...');
            Evaluation::query()->delete();
            $this->info('All evaluations deleted.');
        }

        $employees = Employee::where('status', 'active')->get();
        $periods = ['Q1 2026', 'Q2 2026', 'Q3 2026', 'Q4 2025'];
        $hrManagerId = 1; // Adjust based on your HR manager user ID

        $this->info("Updating evaluations for {$employees->count()} employees...");

        $bar = $this->output->createProgressBar($employees->count() * count($periods));
        $bar->start();

        foreach ($employees as $employee) {
            foreach ($periods as $period) {
                // Calculate scores from real data
                $scores = $calculator->calculateEmployeeScores($employee, $period);

                // Calculate fuzzy score
                $fuzzyResult = $fuzzyService->calculatePerformance(
                    $scores['kpi_score'],
                    $scores['attendance_rate'],
                    $scores['customer_satisfaction']
                );

                // Check if evaluation already exists
                $existing = Evaluation::where('employee_id', $employee->id)
                    ->where('evaluation_period', $period)
                    ->first();

                if ($existing) {
                    // Update existing evaluation
                    $existing->update([
                        'kpi_score' => $scores['kpi_score'],
                        'attendance_rate' => $scores['attendance_rate'],
                        'customer_satisfaction' => $scores['customer_satisfaction'],
                        'fuzzy_score' => $fuzzyResult['fuzzy_score'],
                        'category' => $fuzzyResult['category'],
                        'hr_recommendation' => $fuzzyResult['hr_recommendation'],
                        'fuzzification_details' => $fuzzyResult,
                    ]);
                } else {
                    // Create new evaluation
                    Evaluation::create([
                        'employee_id' => $employee->id,
                        'evaluator_id' => $hrManagerId,
                        'evaluation_period' => $period,
                        'kpi_score' => $scores['kpi_score'],
                        'attendance_rate' => $scores['attendance_rate'],
                        'customer_satisfaction' => $scores['customer_satisfaction'],
                        'fuzzy_score' => $fuzzyResult['fuzzy_score'],
                        'category' => $fuzzyResult['category'],
                        'hr_recommendation' => $fuzzyResult['hr_recommendation'],
                        'fuzzification_details' => $fuzzyResult,
                    ]);
                }

                $bar->advance();
            }
        }

        $bar->finish();
        $this->newLine();
        $this->info('Evaluations updated successfully!');

        return Command::SUCCESS;
    }
}