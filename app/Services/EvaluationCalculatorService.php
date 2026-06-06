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
     * Throws exception if employee doesn't have minimum data for evaluation.
     *
     * @param  string|null  $period  Filter by period (e.g., "2026-06", "Q1 2026") - DEPRECATED, use date range instead
     * @param  string|null  $startDate  Filter from start date (Y-m-d format)
     * @param  string|null  $endDate  Filter to end date (Y-m-d format)
     *
     * @throws \Exception If employee doesn't have minimum data required
     */
    public function calculateEmployeeScores(Employee $employee, ?string $period = null, ?string $startDate = null, ?string $endDate = null): array
    {
        // Validate date range if provided
        if ($startDate && $endDate) {
            $start = \DateTime::createFromFormat('Y-m-d', $startDate);
            $end = \DateTime::createFromFormat('Y-m-d', $endDate);

            if (! $start || ! $end) {
                throw new \Exception('Format tanggal tidak valid. Gunakan format YYYY-MM-DD (contoh: 2026-06-01)');
            }

            if ($start > $end) {
                throw new \Exception('Tanggal start tidak boleh lebih besar dari tanggal end');
            }

            $interval = $start->diff($end);
            if ($interval->days > 365) {
                throw new \Exception('Periode penilaian maksimal 1 tahun. Silakan pilih periode yang lebih pendek');
            }
        }

        // Check if employee has minimum data for evaluation
        $dataStatus = $this->checkEmployeeDataStatus($employee, $period, $startDate, $endDate);

        if (! $dataStatus['has_minimal_data']) {
            $missingReasons = [];
            if (! $dataStatus['has_kpi_data']) {
                $missingReasons[] = 'Tidak ada data KPI (target/report)';
            }
            if (! $dataStatus['has_attendance_data']) {
                $missingReasons[] = 'Tidak ada data kehadiran';
            }
            if (! $dataStatus['has_satisfaction_data']) {
                $missingReasons[] = 'Tidak ada data kepuasan pelanggan';
            }

            throw new \Exception('Data tidak lengkap: '.implode(', ', $missingReasons));
        }

        return [
            'kpi_score' => $this->calculateKpiScore($employee, $period, $startDate, $endDate),
            'attendance_rate' => $this->calculateAttendanceRate($employee, $period, $startDate, $endDate),
            'customer_satisfaction' => $this->calculateCustomerSatisfaction($employee, $period, $startDate, $endDate),
            'details' => [
                'kpi' => $this->getKpiDetails($employee, $period, $startDate, $endDate),
                'attendance' => $this->getAttendanceDetails($employee, $period, $startDate, $endDate),
                'satisfaction' => $this->getSatisfactionDetails($employee, $period, $startDate, $endDate),
            ],
        ];
    }

    /**
     * Check if employee has minimal data for evaluation.
     * Employee needs at least some data in all three categories.
     */
    public function checkEmployeeDataStatus(Employee $employee, ?string $period = null, ?string $startDate = null, ?string $endDate = null): array
    {
        // Check KPI data
        $kpiTargets = $this->getKpiTargets($employee, $period, $startDate, $endDate);
        $hasKpiData = $kpiTargets->isNotEmpty();

        // Check attendance data
        $attendanceQuery = AttendanceEntry::with('schedule')
            ->where('employee_id', $employee->id)
            ->whereHas('schedule', function ($q) {
                $q->where('is_working_day', true);
            });

        if ($startDate && $endDate) {
            $attendanceQuery->whereHas('schedule', function ($q) use ($startDate, $endDate) {
                $q->whereBetween('schedule_date', [$startDate, $endDate]);
            });
        } elseif ($period) {
            $attendanceQuery->whereHas('schedule', function ($q) use ($period) {
                if (preg_match('/^\d{4}-\d{2}$/', $period)) {
                    $q->whereYear('schedule_date', substr($period, 0, 4))
                        ->whereMonth('schedule_date', substr($period, 5, 2));
                }
            });
        }

        $hasAttendanceData = $attendanceQuery->count() > 0;

        // Check satisfaction data
        $satisfactionQuery = CustomerSatisfactionScore::where('employee_id', $employee->id);

        if ($startDate && $endDate) {
            $satisfactionQuery->whereBetween('created_at', [$startDate.' 00:00:00', $endDate.' 23:59:59']);
        } elseif ($period) {
            $periods = $this->mapPeriodToSatisfactionPeriods($period);
            if (! empty($periods)) {
                $satisfactionQuery->whereIn('period', $periods);
            }
        }

        $hasSatisfactionData = $satisfactionQuery->count() > 0;

        return [
            'has_minimal_data' => $hasKpiData && $hasAttendanceData && $hasSatisfactionData,
            'has_kpi_data' => $hasKpiData,
            'has_attendance_data' => $hasAttendanceData,
            'has_satisfaction_data' => $hasSatisfactionData,
        ];
    }

    /**
     * Calculate KPI Score (0-100)
     * Only counts targets that have reports. 5 of 10 targets with reports = calculate based on those 5.
     */
    public function calculateKpiScore(Employee $employee, ?string $period = null): float
    {
        $targets = $this->getKpiTargets($employee, $period);

        if ($targets->isEmpty()) {
            return 0.0; // No targets = no score
        }

        $totalScore = 0;
        $targetsWithReports = 0;

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
                $targetsWithReports++;
            }
            // If no report, skip this target entirely (don't assume perfect score)
        }

        return $targetsWithReports > 0 ? $totalScore / $targetsWithReports : 0.0;
    }

    /**
     * Calculate Attendance Rate (0-100)
     * Based on actual attendance entries. No data = 0%.
     */
    public function calculateAttendanceRate(Employee $employee, ?string $period = null, ?string $startDate = null, ?string $endDate = null): float
    {
        $query = AttendanceEntry::with('schedule')
            ->where('employee_id', $employee->id)
            ->whereHas('schedule', function ($q) {
                $q->where('is_working_day', true);
            });

        // Filter by date range if provided
        if ($startDate && $endDate) {
            $query->whereHas('schedule', function ($q) use ($startDate, $endDate) {
                $q->whereBetween('schedule_date', [$startDate, $endDate]);
            });
        } elseif ($period) {
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
            return 0.0; // No attendance data = 0% (not perfect score)
        }

        $present = $entries->whereIn('status', ['present', 'late'])->count();
        $total = $entries->count();

        return $total > 0 ? ($present / $total) * 100 : 0.0;
    }

    /**
     * Calculate Customer Satisfaction Score (1-10)
     * Only counts available survey responses. No data = minimum score (1.0).
     */
    public function calculateCustomerSatisfaction(Employee $employee, ?string $period = null, ?string $startDate = null, ?string $endDate = null): float
    {
        $query = CustomerSatisfactionScore::where('employee_id', $employee->id);

        // Filter by date range if provided
        if ($startDate && $endDate) {
            $query->whereBetween('created_at', [$startDate.' 00:00:00', $endDate.' 23:59:59']);
        } elseif ($period) {
            $periods = $this->mapPeriodToSatisfactionPeriods($period);
            if (! empty($periods)) {
                $query->whereIn('period', $periods);
            }
        }

        $scores = $query->get();

        if ($scores->isEmpty()) {
            return 1.0; // No scores = minimum score (not perfect 10.0)
        }

        $total = $scores->sum('score');
        $count = $scores->count();

        $average = $count > 0 ? $total / $count : 1.0;

        // Ensure within 1-10 range
        return max(1.0, min(10.0, $average));
    }

    /**
     * Map evaluation period to satisfaction score periods
     */
    protected function mapPeriodToSatisfactionPeriods(string $period): array
    {
        $periods = [];

        // Quarterly periods
        if (preg_match('/Q1 (\d{4})/i', $period, $matches)) {
            $year = $matches[1];
            $periods = ["{$year}-01", "{$year}-02", "{$year}-03"];
        } elseif (preg_match('/Q2 (\d{4})/i', $period, $matches)) {
            $year = $matches[1];
            $periods = ["{$year}-04", "{$year}-05", "{$year}-06"];
        } elseif (preg_match('/Q3 (\d{4})/i', $period, $matches)) {
            $year = $matches[1];
            $periods = ["{$year}-07", "{$year}-08", "{$year}-09"];
        } elseif (preg_match('/Q4 (\d{4})/i', $period, $matches)) {
            $year = $matches[1];
            $periods = ["{$year}-10", "{$year}-11", "{$year}-12"];
        } elseif (preg_match('/(Januari|Februari|Maret|April|Mei|Juni|Juli|Agustus|September|Oktober|November|Desember)/i', $period)) {
            // Month-based periods
            if (preg_match('/(\d{4})/', $period, $matches)) {
                $year = $matches[1];
                $monthMap = [
                    'Januari' => '01', 'Februari' => '02', 'Maret' => '03',
                    'April' => '04', 'Mei' => '05', 'Juni' => '06',
                    'Juli' => '07', 'Agustus' => '08', 'September' => '09',
                    'Oktober' => '10', 'November' => '11', 'Desember' => '12',
                ];
                foreach ($monthMap as $monthName => $monthNum) {
                    if (stripos($period, $monthName) !== false) {
                        $periods = ["{$year}-{$monthNum}"];
                        break;
                    }
                }
            }
        }

        return $periods;
    }

    /**
     * Get KPI details for display
     */
    protected function getKpiDetails(Employee $employee, ?string $period = null, ?string $startDate = null, ?string $endDate = null): array
    {
        $targets = $this->getKpiTargets($employee, $period, $startDate, $endDate);

        $details = [
            'has_targets' => $targets->isNotEmpty(),
            'target_count' => $targets->count(),
            'reports_submitted' => 0,
            'targets_with_reports' => 0,
            'targets' => [],
        ];

        foreach ($targets as $target) {
            // Get report for this KPI target
            $report = KpiReport::where('kpi_target_id', $target->id)->first();

            $targetDetails = [
                'title' => $target->title,
                'period' => $target->period,
                'target_value' => $target->target_value,
                'unit' => $target->unit,
                'has_report' => $report !== null,
                'achievement' => null,
                'actual_value' => 'No report',
            ];

            if ($report) {
                $achievement = 0;
                if ($target->target_value > 0) {
                    $achievement = ($report->actual_value / $target->target_value) * 100;
                    $achievement = min(100, $achievement); // Cap at 100%
                }
                $targetDetails['achievement'] = $achievement;
                $targetDetails['actual_value'] = $report->actual_value;
                $details['reports_submitted']++;
                $details['targets_with_reports']++;
            }

            $details['targets'][] = $targetDetails;
        }

        return $details;
    }

    /**
     * Get Attendance details for display
     */
    protected function getAttendanceDetails(Employee $employee, ?string $period = null, ?string $startDate = null, ?string $endDate = null): array
    {
        $query = AttendanceEntry::with('schedule')
            ->where('employee_id', $employee->id)
            ->whereHas('schedule', function ($q) {
                $q->where('is_working_day', true);
            });

        if ($startDate && $endDate) {
            $query->whereHas('schedule', function ($q) use ($startDate, $endDate) {
                $q->whereBetween('schedule_date', [$startDate, $endDate]);
            });
        } elseif ($period) {
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
            'attendance_rate' => $total > 0 ? ($present / $total) * 100 : 0.0,
        ];
    }

    /**
     * Get Customer Satisfaction details for display
     */
    protected function getSatisfactionDetails(Employee $employee, ?string $period = null, ?string $startDate = null, ?string $endDate = null): array
    {
        $query = CustomerSatisfactionScore::where('employee_id', $employee->id);

        if ($startDate && $endDate) {
            $query->whereBetween('created_at', [$startDate.' 00:00:00', $endDate.' 23:59:59']);
        } elseif ($period) {
            $periods = $this->mapPeriodToSatisfactionPeriods($period);
            if (! empty($periods)) {
                $query->whereIn('period', $periods);
            }
        }

        $scores = $query->latest()->get();

        $average = $scores->isNotEmpty() ? $scores->avg('score') : null;

        return [
            'has_scores' => $scores->isNotEmpty(),
            'score_count' => $scores->count(),
            'average_score' => $average,
            'highest_score' => $scores->isNotEmpty() ? $scores->max('score') : null,
            'lowest_score' => $scores->isNotEmpty() ? $scores->min('score') : null,
            'recent_scores' => $scores->take(5),
        ];
    }

    /**
     * Get KPI targets for an employee filtered by period
     */
    protected function getKpiTargets(Employee $employee, ?string $period = null, ?string $startDate = null, ?string $endDate = null)
    {
        $query = KpiTarget::where('employee_id', $employee->id);

        // Filter by date range if provided
        if ($startDate && $endDate) {
            $query->whereBetween('start_date', [$startDate, $endDate])
                ->orWhereBetween('end_date', [$startDate, $endDate]);
        }

        // Don't filter by period for KPI targets - take ALL active targets
        // KPI targets with period="daily" should be counted regardless of evaluation period
        // Example: Daily sales target applies to any evaluation period

        return $query->active()->get();
    }
}
