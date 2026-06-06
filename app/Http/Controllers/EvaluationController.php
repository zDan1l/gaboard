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

        return view('evaluations.create', [
            'employees' => $employees,
        ]);
    }

    /**
     * Store a newly created evaluation
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'employee_id' => 'required|exists:employees,id',
            'start_date' => 'required|date|before_or_equal:end_date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'notes' => 'nullable|string|max:1000',
        ]);

        try {
            $employee = Employee::with('user')->findOrFail($validated['employee_id']);

            // Validate date range
            $startDate = \DateTime::createFromFormat('Y-m-d', $validated['start_date']);
            $endDate = \DateTime::createFromFormat('Y-m-d', $validated['end_date']);

            if (! $startDate || ! $endDate) {
                throw new \Exception('Format tanggal tidak valid. Gunakan format YYYY-MM-DD');
            }

            if ($startDate > $endDate) {
                throw new \Exception('Tanggal start tidak boleh lebih besar dari tanggal end');
            }

            $interval = $startDate->diff($endDate);
            if ($interval->days > 365) {
                throw new \Exception('Periode penilaian maksimal 1 tahun. Silakan pilih periode yang lebih pendek');
            }

            // Auto-calculate scores from real data
            $scores = $this->calculatorService->calculateEmployeeScores(
                $employee,
                null, // period is deprecated
                $validated['start_date'],
                $validated['end_date']
            );

            // Calculate fuzzy score
            $result = $this->fuzzyService->calculatePerformance(
                $scores['kpi_score'],
                $scores['attendance_rate'],
                $scores['customer_satisfaction']
            );

            // Check for existing evaluation for this employee and date range
            $existingEvaluation = Evaluation::where('employee_id', $validated['employee_id'])
                ->where('start_date', $validated['start_date'])
                ->where('end_date', $validated['end_date'])
                ->first();

            if ($existingEvaluation) {
                return redirect()->route('evaluations.index')
                    ->with('error', 'Penilaian untuk karyawan ini pada periode '.$validated['start_date'].' s/d '.$validated['end_date'].' sudah ada. Silakan edit penilaian yang sudah ada atau gunakan periode yang berbeda.');
            }

            // Check for overlapping evaluation periods
            $overlapPeriod = Evaluation::where('employee_id', $validated['employee_id'])
                ->where(function ($query) use ($validated) {
                    $query->where(function ($q) use ($validated) {
                        // Existing period overlaps with new period
                        $q->where('start_date', '<=', $validated['end_date'])
                            ->where('end_date', '>=', $validated['start_date']);
                    });
                })
                ->first();

            if ($overlapPeriod) {
                $overlapStart = $overlapPeriod->start_date->format('d M Y');
                $overlapEnd = $overlapPeriod->end_date->format('d M Y');

                return redirect()->route('evaluations.index')
                    ->with('error', "Periode penilaian bertumpang tindih (overlap) dengan penilaian yang sudah ada: {$overlapStart} s/d {$overlapEnd}. Silakan pilih periode yang tidak overlap.");
            }

            // Create evaluation
            $evaluation = Evaluation::create([
                'employee_id' => $validated['employee_id'],
                'evaluator_id' => Auth::id(),
                'evaluation_period' => $validated['start_date'].' s/d '.$validated['end_date'],
                'start_date' => $validated['start_date'],
                'end_date' => $validated['end_date'],
                'kpi_score' => $scores['kpi_score'],
                'attendance_rate' => $scores['attendance_rate'],
                'customer_satisfaction' => $scores['customer_satisfaction'],
                'fuzzy_score' => $result['fuzzy_score'],
                'category' => $result['category'],
                'hr_recommendation' => $result['hr_recommendation'],
                'fuzzification_details' => $result,
                'notes' => $validated['notes'] ?? null,
            ]);

            return redirect()->route('evaluations.show', $evaluation)
                ->with('success', 'Penilaian berhasil disimpan. Skor Fuzzy: '.$result['fuzzy_score'].' ('.$result['category'].')');

        } catch (\Exception $e) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Gagal membuat penilaian: '.$e->getMessage());
        }
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
     * Batch generate evaluations for all employees
     */
    public function batchGenerate(Request $request)
    {
        if (Auth::user()->role->slug !== 'hr_manager') {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
        }

        $validated = $request->validate([
            'start_date' => 'required|date|before_or_equal:end_date',
            'end_date' => 'required|date|after_or_equal:start_date',
        ]);

        $startDate = $validated['start_date'];
        $endDate = $validated['end_date'];

        // Validate date range
        $start = \DateTime::createFromFormat('Y-m-d', $startDate);
        $end = \DateTime::createFromFormat('Y-m-d', $endDate);

        if (! $start || ! $end) {
            return response()->json([
                'success' => false,
                'message' => 'Format tanggal tidak valid. Gunakan format YYYY-MM-DD',
            ], 422);
        }

        if ($start > $end) {
            return response()->json([
                'success' => false,
                'message' => 'Tanggal start tidak boleh lebih besar dari tanggal end',
            ], 422);
        }

        $interval = $start->diff($end);
        if ($interval->days > 365) {
            return response()->json([
                'success' => false,
                'message' => 'Periode penilaian maksimal 1 tahun. Silakan pilih periode yang lebih pendek',
            ], 422);
        }

        // Get all active employees (not HR managers)
        $employees = Employee::with('user')
            ->where('status', 'active')
            ->whereHas('user.role', function ($query) {
                $query->where('slug', 'employee');
            })
            ->get();

        $created = 0;
        $skippedExists = 0;
        $skippedNoData = [];
        $errors = [];

        foreach ($employees as $employee) {
            try {
                // Check if evaluation already exists for this employee and date range
                $existing = Evaluation::where('employee_id', $employee->id)
                    ->where('start_date', $startDate)
                    ->where('end_date', $endDate)
                    ->first();

                if ($existing) {
                    $skippedExists++;

                    continue;
                }

                // Check for overlapping evaluation periods
                $overlapPeriod = Evaluation::where('employee_id', $employee->id)
                    ->where(function ($query) use ($startDate, $endDate) {
                        $query->where(function ($q) use ($startDate, $endDate) {
                            // Existing period overlaps with new period
                            $q->where('start_date', '<=', $endDate)
                              ->where('end_date', '>=', $startDate);
                        });
                    })
                    ->first();

                if ($overlapPeriod) {
                    $overlapStart = $overlapPeriod->start_date->format('d M Y');
                    $overlapEnd = $overlapPeriod->end_date->format('d M Y');

                    $skippedNoData[] = [
                        'name' => $employee->user->name,
                        'reason' => "Overlap dengan penilaian: {$overlapStart} s/d {$overlapEnd}",
                    ];
                    continue;
                }

                // Calculate scores automatically (will throw exception if no data)
                $scores = $this->calculatorService->calculateEmployeeScores(
                    $employee,
                    null, // period is deprecated
                    $startDate,
                    $endDate
                );

                // Calculate fuzzy score
                $result = $this->fuzzyService->calculatePerformance(
                    $scores['kpi_score'],
                    $scores['attendance_rate'],
                    $scores['customer_satisfaction']
                );

                // Create evaluation
                Evaluation::create([
                    'employee_id' => $employee->id,
                    'evaluator_id' => Auth::id(),
                    'evaluation_period' => $startDate.' s/d '.$endDate,
                    'start_date' => $startDate,
                    'end_date' => $endDate,
                    'kpi_score' => $scores['kpi_score'],
                    'attendance_rate' => $scores['attendance_rate'],
                    'customer_satisfaction' => $scores['customer_satisfaction'],
                    'fuzzy_score' => $result['fuzzy_score'],
                    'category' => $result['category'],
                    'hr_recommendation' => $result['hr_recommendation'],
                    'fuzzification_details' => $result,
                ]);

                $created++;
            } catch (\Exception $e) {
                // Check if this is a "no data" exception
                if (strpos($e->getMessage(), 'Data tidak lengkap') === 0) {
                    $skippedNoData[] = [
                        'name' => $employee->user->name,
                        'reason' => $e->getMessage(),
                    ];
                } else {
                    $errors[] = "Employee {$employee->user->name}: ".$e->getMessage();
                }
            }
        }

        $message = "✅ Berhasil membuat {$created} penilaian.";

        if ($skippedExists > 0) {
            $message .= " ⏭️ {$skippedExists} dilewati (sudah ada).";
        }

        if (count($skippedNoData) > 0) {
            $message .= ' ⚠️ '.count($skippedNoData).' dilewati (data tidak lengkap):';
            foreach ($skippedNoData as $skipped) {
                $message .= "\n  • {$skipped['name']}: {$skipped['reason']}";
            }
        }

        return response()->json([
            'success' => true,
            'created' => $created,
            'skipped_exists' => $skippedExists,
            'skipped_no_data' => $skippedNoData,
            'errors' => $errors,
            'message' => $message,
        ]);
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
