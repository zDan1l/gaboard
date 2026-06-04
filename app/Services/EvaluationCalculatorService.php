<?php

namespace App\Services;

use App\Models\AttendanceEntry;
use App\Models\CustomerSatisfactionScore;
use App\Models\Employee;
use App\Models\KpiReport;
use App\Models\KpiTarget;

class EvaluationCalculatorService
{
    /**
     * Calculate all evaluation scores for an employee based on actual data.
     * Uses "No Data = Perfect Score" logic - employee shouldn't be penalized for lack of data.
     *
     * @param  string|null  $period  Filter by period (e.g., "2026-06", "Q1 2026")
     */
    public function calculateEmployeeScores(Employee $employee, ?string $period = null): array
    {
        return [
            'kpi_score' => $this->calculateKpiScore($employee, $period),
            'attendance_rate' => $this->calculateAttendanceRate($employee, $period),
            'customer_satisfaction' => $this->calculateCustomerSatisfaction($employee, $period),
            'details' => [
                'kpi' => $this->getKpiDetails($employee, $period),
                'attendance' => $this->getAttendanceDetails($employee, $period),
                'satisfaction' => $this->getSatisfactionDetails($employee, $period),
            ],
        ];
    }

    /**
     * Calculate KPI Score (0-100)
     * If no targets/reports exist, returns 100 (perfect score - not employee's fault)
     */
    public function calculateKpiScore(Employee $employee, ?string $period = null): float
    {
        $targets = $this->getKpiTargets($employee, $period);

        if ($targets->isEmpty()) {
            return 100.0; // No targets = perfect score
        }

        $totalScore = 0;
        $targetCount = 0;

        foreach ($targets as $target) {
            // Get report for this KPI target
            $report = KpiReport::where('kpi_target_id', $target->id)->first();

            if ($report) {
                // Has report - calculate actual achievement
                $achievement = 0;
                if ($target->target_value > 0) {
                    $achievement = ($report->actual_value / $target->target_value) * 100;
                }
                $totalScore += min(100, $achievement); // Cap at 100%
                $targetCount++;
            } else {
                // Has target but no report - assume 100% achievement
                // (Not employee's fault if HR hasn't created report yet)
                $totalScore += 100.0;
                $targetCount++;
            }
        }

        return $targetCount > 0 ? $totalScore / $targetCount : 100.0;
    }

    /**
     * Calculate Attendance Rate (0-100)
     * Based on actual attendance entries
     */
    public function calculateAttendanceRate(Employee $employee, ?string $period = null): float
    {
        $query = AttendanceEntry::with('schedule')
            ->where('employee_id', $employee->id)
            ->whereHas('schedule', function ($q) {
                $q->where('is_working_day', true);
            });

        // Filter by period if provided
        if ($period) {
            $query->whereHas('schedule', function ($q) use ($period) {
                if (preg_match('/^\d{4}-\d{2}$/', $period)) {
                    // Format: 2026-06
                    $q->whereYear('schedule_date', substr($period, 0, 4))
                        ->whereMonth('schedule_date', substr($period, 5, 2));
                }
            });
        }

        $entries = $query->get();

        if ($entries->isEmpty()) {
            return 100.0; // No attendance data = perfect score
        }

        $present = $entries->whereIn('status', ['present', 'late'])->count();
        $total = $entries->count();

        return $total > 0 ? ($present / $total) * 100 : 100.0;
    }

    /**
     * Calculate Customer Satisfaction Score (1-10)
     * If no scores exist, returns 10 (perfect score - not employee's fault)
     */
    public function calculateCustomerSatisfaction(Employee $employee, ?string $period = null): float
    {
        $query = CustomerSatisfactionScore::where('employee_id', $employee->id);

        // Filter by period if provided
        if ($period) {
            $query->where('period', $period);
        }

        $scores = $query->get();

        if ($scores->isEmpty()) {
            return 10.0; // No scores = perfect score (max on 1-10 scale)
        }

        $total = $scores->sum('score');
        $count = $scores->count();

        $average = $count > 0 ? $total / $count : 10.0;

        // Ensure within 1-10 range
        return max(1.0, min(10.0, $average));
    }

    /**
     * Get KPI details for display
     */
    protected function getKpiDetails(Employee $employee, ?string $period = null): array
    {
        $targets = $this->getKpiTargets($employee, $period);

        $details = [
            'has_targets' => $targets->isNotEmpty(),
            'target_count' => $targets->count(),
            'reports_submitted' => 0,
            'targets' => [],
        ];

        foreach ($targets as $target) {
            // Get report for this KPI target (any user who reported it)
            $report = KpiReport::where('kpi_target_id', $target->id)->first();

            // Calculate achievement percentage
            $achievement = 100.0; // Default if no report
            $actualValue = 'N/A';

            if ($report) {
                if ($target->target_value > 0) {
                    $achievement = ($report->actual_value / $target->target_value) * 100;
                    $achievement = min(100, $achievement); // Cap at 100%
                }
                $actualValue = $report->actual_value;
            }

            $targetDetails = [
                'title' => $target->title,
                'period' => $target->period,
                'target_value' => $target->target_value,
                'unit' => $target->unit,
                'has_report' => $report !== null,
                'achievement' => $achievement,
                'actual_value' => $actualValue,
            ];

            if ($report) {
                $details['reports_submitted']++;
            }

            $details['targets'][] = $targetDetails;
        }

        return $details;
    }

    /**
     * Get Attendance details for display
     */
    protected function getAttendanceDetails(Employee $employee, ?string $period = null): array
    {
        $query = AttendanceEntry::with('schedule')
            ->where('employee_id', $employee->id)
            ->whereHas('schedule', function ($q) {
                $q->where('is_working_day', true);
            });

        if ($period) {
            $query->whereHas('schedule', function ($q) use ($period) {
                if (preg_match('/^\d{4}-\d{2}$/', $period)) {
                    $q->whereYear('schedule_date', substr($period, 0, 4))
                        ->whereMonth('schedule_date', substr($period, 5, 2));
                }
            });
        }

        $entries = $query->get();

        $present = $entries->whereIn('status', ['present', 'late'])->count();
        $late = $entries->where('status', 'late')->count();
        $absent = $entries->where('status', 'absent')->count();
        $excused = $entries->where('status', 'excused')->count();
        $total = $entries->count();

        return [
            'has_data' => $entries->isNotEmpty(),
            'total_working_days' => $total,
            'present' => $present,
            'late' => $late,
            'absent' => $absent,
            'excused' => $excused,
            'attendance_rate' => $total > 0 ? ($present / $total) * 100 : 100.0,
        ];
    }

    /**
     * Get Customer Satisfaction details for display
     */
    protected function getSatisfactionDetails(Employee $employee, ?string $period = null): array
    {
        $query = CustomerSatisfactionScore::where('employee_id', $employee->id);

        if ($period) {
            $query->where('period', $period);
        }

        $scores = $query->latest()->get();

        $average = $scores->isNotEmpty() ? $scores->avg('score') : 10.0;

        return [
            'has_scores' => $scores->isNotEmpty(),
            'score_count' => $scores->count(),
            'average_score' => $average,
            'highest_score' => $scores->isNotEmpty() ? $scores->max('score') : 10.0,
            'lowest_score' => $scores->isNotEmpty() ? $scores->min('score') : 10.0,
            'recent_scores' => $scores->take(5),
        ];
    }

    /**
     * Get KPI targets for an employee filtered by period
     */
    protected function getKpiTargets(Employee $employee, ?string $period = null)
    {
        $query = KpiTarget::where('employee_id', $employee->id);

        // Filter by period if provided (note: period is enum: daily, weekly, monthly, custom)
        if ($period) {
            // Map evaluation period to KPI target period type
            // For example: "Januari 2026" -> "monthly"
            if (preg_match('/(Januari|Februari|Maret|April|Mei|Juni|Juli|Agustus|September|Oktober|November|Desember)/i', $period)) {
                $query->where('period', 'monthly');
            } elseif (preg_match('/Q[1-4]/i', $period)) {
                $query->where('period', 'monthly'); // Quarterly targets use monthly
            }
        }

        return $query->active()->get();
    }
}
