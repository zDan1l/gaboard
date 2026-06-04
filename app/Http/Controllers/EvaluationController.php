<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\Evaluation;
use App\Services\EvaluationCalculatorService;
use App\Services\FuzzyLogicService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EvaluationController extends Controller
{
    protected FuzzyLogicService $fuzzyService;

    protected EvaluationCalculatorService $calculatorService;

    public function __construct(FuzzyLogicService $fuzzyService, EvaluationCalculatorService $calculatorService)
    {
        $this->fuzzyService = $fuzzyService;
        $this->calculatorService = $calculatorService;
        $this->middleware('auth');
    }

    /**
     * Display list of all evaluations
     */
    public function index()
    {
        $user = Auth::user();

        if ($user->role->slug === 'hr_manager') {
            // HR/Manager can see all evaluations
            $evaluations = Evaluation::with(['employee.user', 'evaluator'])
                ->orderBy('created_at', 'desc')
                ->paginate(20);
        } else {
            // Employees can only see their own evaluations
            $evaluations = Evaluation::with(['employee.user', 'evaluator'])
                ->where('employee_id', $user->employee->id)
                ->orderBy('created_at', 'desc')
                ->paginate(20);
        }

        return view('evaluations.index', compact('evaluations'));
    }

    /**
     * Show the form for creating a new evaluation
     */
    public function create()
    {
        $user = Auth::user();

        if ($user->role->slug === 'hr_manager') {
            // HR/Manager can evaluate all active regular employees (not other HR Managers)
            $employees = Employee::with('user')
                ->where('status', 'active')
                ->whereHas('user.role', function ($query) {
                    $query->where('slug', 'employee');
                })
                ->get();
        } else {
            return redirect()->route('evaluations.index')
                ->with('error', 'Anda tidak memiliki akses untuk melakukan penilaian.');
        }

        $periods = $this->generateEvaluationPeriods();

        return view('evaluations.create', compact('employees', 'periods'));
    }

    /**
     * Auto-calculate evaluation scores for an employee
     */
    public function autoCalculate(Request $request)
    {
        $validated = $request->validate([
            'employee_id' => 'required|exists:employees,id',
            'period' => 'nullable|string',
        ]);

        $employee = Employee::with('user')->findOrFail($validated['employee_id']);

        $scores = $this->calculatorService->calculateEmployeeScores($employee, $validated['period'] ?? null);

        return response()->json([
            'kpi_score' => $scores['kpi_score'],
            'attendance_rate' => $scores['attendance_rate'],
            'customer_satisfaction' => $scores['customer_satisfaction'],
            'details' => $scores['details'],
        ]);
    }

    /**
     * Store a newly created evaluation
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'employee_id' => 'required|exists:employees,id',
            'evaluation_period' => 'required|string|max:50',
            'kpi_score' => 'required|numeric|min:0|max:100',
            'attendance_rate' => 'required|numeric|min:0|max:100',
            'customer_satisfaction' => 'required|numeric|min:1|max:10',
            'notes' => 'nullable|string|max:1000',
        ]);

        // Calculate fuzzy score
        $result = $this->fuzzyService->calculatePerformance(
            $validated['kpi_score'],
            $validated['attendance_rate'],
            $validated['customer_satisfaction']
        );

        // Create evaluation
        $evaluation = Evaluation::create([
            'employee_id' => $validated['employee_id'],
            'evaluator_id' => Auth::id(),
            'evaluation_period' => $validated['evaluation_period'],
            'kpi_score' => $validated['kpi_score'],
            'attendance_rate' => $validated['attendance_rate'],
            'customer_satisfaction' => $validated['customer_satisfaction'],
            'fuzzy_score' => $result['fuzzy_score'],
            'category' => $result['category'],
            'hr_recommendation' => $result['hr_recommendation'],
            'fuzzification_details' => $result,
            'notes' => $validated['notes'] ?? null,
        ]);

        return redirect()->route('evaluations.show', $evaluation)
            ->with('success', 'Penilaian berhasil disimpan. Skor Fuzzy: '.$result['fuzzy_score']);
    }

    /**
     * Display the specified evaluation
     */
    public function show(Evaluation $evaluation)
    {
        $this->authorizeView($evaluation);

        $evaluation->load(['employee.user', 'employee.department', 'evaluator']);

        return view('evaluations.show', compact('evaluation'));
    }

    /**
     * Show the form for editing the specified evaluation
     */
    public function edit(Evaluation $evaluation)
    {
        $this->authorizeEdit($evaluation);

        $evaluation->load('employee.user');

        $periods = $this->generateEvaluationPeriods();

        return view('evaluations.edit', compact('evaluation', 'periods'));
    }

    /**
     * Update the specified evaluation
     */
    public function update(Request $request, Evaluation $evaluation)
    {
        $this->authorizeEdit($evaluation);

        $validated = $request->validate([
            'evaluation_period' => 'required|string|max:50',
            'kpi_score' => 'required|numeric|min:0|max:100',
            'attendance_rate' => 'required|numeric|min:0|max:100',
            'customer_satisfaction' => 'required|numeric|min:1|max:10',
            'notes' => 'nullable|string|max:1000',
        ]);

        // Recalculate fuzzy score
        $result = $this->fuzzyService->calculatePerformance(
            $validated['kpi_score'],
            $validated['attendance_rate'],
            $validated['customer_satisfaction']
        );

        $evaluation->update([
            'evaluation_period' => $validated['evaluation_period'],
            'kpi_score' => $validated['kpi_score'],
            'attendance_rate' => $validated['attendance_rate'],
            'customer_satisfaction' => $validated['customer_satisfaction'],
            'fuzzy_score' => $result['fuzzy_score'],
            'category' => $result['category'],
            'hr_recommendation' => $result['hr_recommendation'],
            'fuzzification_details' => $result,
            'notes' => $validated['notes'] ?? null,
        ]);

        return redirect()->route('evaluations.show', $evaluation)
            ->with('success', 'Penilaian berhasil diperbarui. Skor Fuzzy: '.$result['fuzzy_score']);
    }

    /**
     * Remove the specified evaluation
     */
    public function destroy(Evaluation $evaluation)
    {
        $this->authorizeEdit($evaluation);

        $evaluation->delete();

        return redirect()->route('evaluations.index')
            ->with('success', 'Penilaian berhasil dihapus.');
    }

    /**
     * Generate evaluation periods for dropdown
     */
    protected function generateEvaluationPeriods(): array
    {
        $periods = [];
        $year = date('Y');

        // Generate quarters for current and previous year
        for ($y = $year; $y >= $year - 1; $y--) {
            $periods[] = "Q1 {$y}";
            $periods[] = "Q2 {$y}";
            $periods[] = "Q3 {$y}";
            $periods[] = "Q4 {$y}";
        }

        // Generate months for current year
        $months = [
            'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni',
            'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember',
        ];

        foreach ($months as $month) {
            $periods[] = "{$month} {$year}";
        }

        return $periods;
    }

    /**
     * Authorize user to view evaluation
     */
    protected function authorizeView(Evaluation $evaluation): void
    {
        $user = Auth::user();

        if ($user->role->slug === 'employee' &&
            $evaluation->employee_id !== $user->employee->id) {
            abort(403, 'Anda tidak memiliki akses untuk melihat penilaian ini.');
        }
    }

    /**
     * Authorize user to edit evaluation
     */
    protected function authorizeEdit(Evaluation $evaluation): void
    {
        $user = Auth::user();

        if ($user->role->slug !== 'hr_manager') {
            abort(403, 'Anda tidak memiliki akses untuk mengedit penilaian.');
        }

        if ($user->role->slug === 'manager' &&
            $evaluation->employee->manager_id !== $user->employee->id) {
            abort(403, 'Anda tidak memiliki akses untuk mengedit penilaian ini.');
        }
    }
}
