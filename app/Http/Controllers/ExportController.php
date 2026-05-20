<?php

namespace App\Http\Controllers;

use App\Models\Evaluation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ExportController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Export single evaluation to PDF (simplified version)
     */
    public function exportEvaluationPdf(Evaluation $evaluation)
    {
        $this->authorizeExport($evaluation);

        $evaluation->load(['employee.user', 'employee.department', 'evaluator']);

        $filename = 'penilaian_' . str_replace(' ', '_', $evaluation->employee->user->name) . '_' . $evaluation->evaluation_period . '.pdf';

        // For now, return a view that can be printed
        return view('exports.evaluation-pdf', [
            'evaluation' => $evaluation,
            'title' => 'Penilaian Kinerja Karyawan',
        ]);
    }

    /**
     * Export all evaluations to Excel (simplified version)
     */
    public function exportEvaluationsExcel(Request $request)
    {
        $user = Auth::user();

        if ($user->role->slug === 'hr_manager') {
            $evaluations = Evaluation::with(['employee.user', 'employee.department', 'evaluator'])
                ->orderBy('created_at', 'desc')
                ->get();
        } else {
            // Employee can only export their own evaluations
            $evaluations = Evaluation::with(['employee.user', 'employee.department', 'evaluator'])
                ->where('employee_id', $user->employee->id)
                ->orderBy('created_at', 'desc')
                ->get();
        }

        return view('exports.evaluations-excel', [
            'evaluations' => $evaluations,
            'title' => 'Data Penilaian Karyawan',
        ]);
    }

    /**
     * Export summary report
     */
    public function exportSummaryReport(Request $request)
    {
        $user = Auth::user();

        if ($user->role->slug !== 'hr_manager') {
            abort(403, 'Unauthorized access.');
        }

        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');

        $query = Evaluation::with(['employee.user', 'employee.department']);

        if ($startDate) {
            $query->where('created_at', '>=', $startDate);
        }

        if ($endDate) {
            $query->where('created_at', '<=', $endDate);
        }

        $evaluations = $query->get();

        $summary = [
            'total_evaluations' => $evaluations->count(),
            'average_score' => $evaluations->avg('fuzzy_score'),
            'performance_distribution' => [
                'sangat_baik' => $evaluations->where('category', 'sangat_baik')->count(),
                'baik' => $evaluations->where('category', 'baik')->count(),
                'cukup' => $evaluations->where('category', 'cukup')->count(),
                'buruk' => $evaluations->where('category', 'buruk')->count(),
                'sangat_buruk' => $evaluations->where('category', 'sangat_buruk')->count(),
            ],
        ];

        return view('exports.summary-report', [
            'evaluations' => $evaluations,
            'summary' => $summary,
            'filters' => [
                'start_date' => $startDate,
                'end_date' => $endDate,
            ],
            'title' => 'Laporan Ringkas Penilaian',
        ]);
    }

    /**
     * Export department rankings
     */
    public function exportDepartmentRankings(Request $request)
    {
        $user = Auth::user();

        if ($user->role->slug !== 'hr_manager') {
            abort(403, 'Unauthorized access.');
        }

        $rankings = Evaluation::selectRaw('
            employees.department_id,
            departments.name as department_name,
            COUNT(DISTINCT employees.id) as total_employees,
            COUNT(evaluations.id) as total_evaluations,
            AVG(evaluations.fuzzy_score) as average_score,
            SUM(CASE WHEN evaluations.category = "sangat_baik" THEN 1 ELSE 0 END) as sangat_baik_count,
            SUM(CASE WHEN evaluations.category = "baik" THEN 1 ELSE 0 END) as baik_count,
            SUM(CASE WHEN evaluations.category = "cukup" THEN 1 ELSE 0 END) as cukup_count,
            SUM(CASE WHEN evaluations.category = "buruk" THEN 1 ELSE 0 END) as buruk_count,
            SUM(CASE WHEN evaluations.category = "sangat_buruk" THEN 1 ELSE 0 END) as sangat_buruk_count
        ')
            ->join('employees', 'evaluations.employee_id', '=', 'employees.id')
            ->join('departments', 'employees.department_id', '=', 'departments.id')
            ->groupBy('employees.department_id', 'departments.name')
            ->orderBy('average_score', 'desc')
            ->get();

        return view('exports.department-rankings', [
            'rankings' => $rankings,
            'title' => 'Ranking Departemen',
        ]);
    }

    /**
     * Authorize user to export evaluation
     */
    protected function authorizeExport(Evaluation $evaluation): void
    {
        $user = Auth::user();

        if ($user->role->slug === 'employee' &&
            $evaluation->employee_id !== $user->employee->id) {
            abort(403, 'Unauthorized access.');
        }
    }
}
