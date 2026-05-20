<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\Evaluation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    /**
     * Display dashboard based on user role.
     */
    public function index()
    {
        $user = Auth::user();

        // Redirect to different dashboard views based on role
        if ($user->role->slug === 'hr_manager') {
            return $this->hrManagerDashboard();
        } else {
            return $this->employeeDashboard();
        }
    }

    /**
     * HR/Manager Dashboard with comprehensive analytics.
     */
    private function hrManagerDashboard()
    {
        $analytics = $this->getOverallAnalytics();

        return view('dashboard.hr', [
            'title' => 'HR/Manager Dashboard',
            'user' => auth()->user(),
            'totalEmployees' => $analytics['totalEmployees'],
            'totalEvaluations' => $analytics['totalEvaluations'],
            'averageScore' => $analytics['averageScore'],
            'topPerformers' => $analytics['topPerformers'],
            'performanceDistribution' => $analytics['performanceDistribution'],
            'recentEvaluations' => $analytics['recentEvaluations'],
            'departmentAnalytics' => $analytics['departmentAnalytics'],
        ]);
    }

    /**
     * Employee Dashboard with personal analytics.
     */
    private function employeeDashboard()
    {
        $employeeId = auth()->user()->employee->id;
        $analytics = $this->getEmployeeAnalytics($employeeId);

        return view('dashboard.employee', [
            'title' => 'Dashboard Karyawan',
            'user' => auth()->user(),
            'myEvaluations' => $analytics['myEvaluations'],
            'latestScore' => $analytics['latestScore'],
            'performanceTrend' => $analytics['performanceTrend'],
            'averageScore' => $analytics['averageScore'],
            'recommendations' => $analytics['recommendations'],
        ]);
    }

    /**
     * Get overall analytics for HR and Executive views.
     */
    private function getOverallAnalytics(): array
    {
        $totalEmployees = Employee::where('status', 'active')->count();
        $totalEvaluations = Evaluation::count();
        $averageScore = Evaluation::avg('fuzzy_score') ?? 0;

        $topPerformers = Evaluation::with('employee.user')
            ->orderBy('fuzzy_score', 'desc')
            ->limit(10)
            ->get();

        $performanceDistribution = [
            'sangat_baik' => Evaluation::where('category', 'sangat_baik')->count(),
            'baik' => Evaluation::where('category', 'baik')->count(),
            'cukup' => Evaluation::where('category', 'cukup')->count(),
            'buruk' => Evaluation::where('category', 'buruk')->count(),
            'sangat_buruk' => Evaluation::where('category', 'sangat_buruk')->count(),
        ];

        $recentEvaluations = Evaluation::with(['employee.user', 'evaluator'])
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        $departmentAnalytics = Employee::join('evaluations', 'employees.id', '=', 'evaluations.employee_id')
            ->join('departments', 'employees.department_id', '=', 'departments.id')
            ->selectRaw('
                departments.name as department_name,
                COUNT(DISTINCT employees.id) as total_employees,
                COUNT(evaluations.id) as total_evaluations,
                AVG(evaluations.fuzzy_score) as average_score
            ')
            ->groupBy('departments.id', 'departments.name')
            ->get();

        return [
            'totalEmployees' => $totalEmployees,
            'totalEvaluations' => $totalEvaluations,
            'averageScore' => round($averageScore, 2),
            'topPerformers' => $topPerformers,
            'performanceDistribution' => $performanceDistribution,
            'recentEvaluations' => $recentEvaluations,
            'departmentAnalytics' => $departmentAnalytics,
        ];
    }

    /**
     * Get employee-specific analytics.
     */
    private function getEmployeeAnalytics(int $employeeId): array
    {
        $myEvaluations = Evaluation::where('employee_id', $employeeId)
            ->orderBy('created_at', 'desc')
            ->get();

        $latestScore = $myEvaluations->first()?->fuzzy_score ?? 0;
        $averageScore = $myEvaluations->avg('fuzzy_score') ?? 0;

        $performanceTrend = $myEvaluations->take(5)->reverse()->values();

        $recommendations = $myEvaluations->whereIn('category', ['buruk', 'sangat_buruk'])->count();

        return [
            'myEvaluations' => $myEvaluations,
            'latestScore' => round($latestScore, 2),
            'performanceTrend' => $performanceTrend,
            'averageScore' => round($averageScore, 2),
            'recommendations' => $recommendations,
        ];
    }

    /**
     * Get performance trends over time.
     */
    private function getPerformanceTrends(): array
    {
        $monthlyTrends = Evaluation::selectRaw('
            DATE_FORMAT(created_at, "%Y-%m") as period,
            COUNT(*) as total_evaluations,
            AVG(fuzzy_score) as average_score
        ')
            ->groupBy('period')
            ->orderBy('period', 'desc')
            ->limit(12)
            ->get()
            ->reverse()
            ->values();

        return [
            'monthlyTrends' => $monthlyTrends,
        ];
    }
}
