<?php

namespace App\Http\Controllers;

use App\Models\CustomerSatisfactionScore;
use App\Models\Employee;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CustomerSatisfactionScoreController extends Controller
{
    public function index(Request $request)
    {
        $query = CustomerSatisfactionScore::with(['employee', 'ratedBy']);

        if ($request->has('employee_id')) {
            $query->where('employee_id', $request->employee_id);
        }

        if ($request->has('period')) {
            $query->where('period', $request->period);
        }

        $scores = $query->latest()->get();

        return response()->json($scores);
    }

    public function indexView(Request $request)
    {
        $query = CustomerSatisfactionScore::with(['employee.user', 'ratedBy']);

        if ($request->has('employee_id') && $request->employee_id) {
            $query->where('employee_id', $request->employee_id);
        }

        if ($request->has('period') && $request->period) {
            $query->where('period', $request->period);
        }

        $scores = $query->latest()->get();

        // Only get regular employees, not HR Managers
        $employees = Employee::with('user')
            ->whereHas('user.role', function ($query) {
                $query->where('slug', 'employee');
            })->get();

        // Calculate average scores per employee for top performers
        $employeeScores = [];
        foreach ($scores as $score) {
            $empId = $score->employee_id;
            if (! isset($employeeScores[$empId])) {
                $employeeScores[$empId] = ['scores' => [], 'employee' => $score->employee];
            }
            $employeeScores[$empId]['scores'][] = $score->score;
        }

        $topPerformers = collect($employeeScores)->map(function ($data) {
            return [
                'employee' => $data['employee'],
                'average' => collect($data['scores'])->avg(),
                'count' => count($data['scores']),
            ];
        })->sortByDesc('average')->take(8);

        return view('customer-satisfaction.index', compact('scores', 'employees', 'topPerformers'));
    }

    public function createView()
    {
        // Only get regular employees, not HR Managers
        $employees = Employee::with('user')
            ->whereHas('user.role', function ($query) {
                $query->where('slug', 'employee');
            })->get();

        return view('customer-satisfaction.create', compact('employees'));
    }

    public function editView(CustomerSatisfactionScore $customerSatisfactionScore)
    {
        // Only get regular employees, not HR Managers
        $employees = Employee::with('user')
            ->whereHas('user.role', function ($query) {
                $query->where('slug', 'employee');
            })->get();
        $customerSatisfactionScore->load(['employee.user', 'ratedBy']);

        return view('customer-satisfaction.edit', compact('customerSatisfactionScore', 'employees'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'employee_id' => 'required|exists:employees,id',
            'score' => 'required|numeric|min:1|max:5',
            'period' => 'nullable|string|max:50',
            'notes' => 'nullable|string',
        ]);

        $validated['rated_by'] = Auth::id();

        CustomerSatisfactionScore::create($validated);

        return redirect()->route('customer-satisfaction.index')->with('success', 'Nilai kepuasan pelanggan berhasil disimpan!');
    }

    public function show(CustomerSatisfactionScore $customerSatisfactionScore)
    {
        $customerSatisfactionScore->load(['employee', 'ratedBy']);

        return response()->json($customerSatisfactionScore);
    }

    public function update(Request $request, CustomerSatisfactionScore $customerSatisfactionScore)
    {
        $validated = $request->validate([
            'employee_id' => 'sometimes|required|exists:employees,id',
            'score' => 'sometimes|numeric|min:1|max:5',
            'period' => 'nullable|string|max:50',
            'notes' => 'nullable|string',
        ]);

        $customerSatisfactionScore->update($validated);

        return redirect()->route('customer-satisfaction.index')->with('success', 'Nilai kepuasan pelanggan berhasil diperbarui!');
    }

    public function destroy(CustomerSatisfactionScore $customerSatisfactionScore)
    {
        $customerSatisfactionScore->delete();

        return redirect()->route('customer-satisfaction.index')->with('success', 'Nilai kepuasan pelanggan berhasil dihapus!');
    }

    public function myScores()
    {
        $employee = Auth::user()->employee;

        if (! $employee) {
            return response()->json(['message' => 'Employee record not found'], 404);
        }

        $scores = CustomerSatisfactionScore::with('ratedBy')
            ->where('employee_id', $employee->id)
            ->latest()
            ->get();

        return response()->json($scores);
    }

    public function myScoresView()
    {
        $employee = Auth::user()->employee;

        if (! $employee) {
            return redirect()->route('dashboard')->with('error', 'Data karyawan tidak ditemukan!');
        }

        $myScores = CustomerSatisfactionScore::with('ratedBy')
            ->where('employee_id', $employee->id)
            ->latest()
            ->get();

        // Calculate average and ranking
        $average = $myScores->count() > 0 ? $myScores->avg('score') : 0;
        $totalScores = $myScores->count();

        // Group by period for chart
        $periodScores = [];
        foreach ($myScores as $score) {
            $period = $score->period ?? 'Unknown';
            if (! isset($periodScores[$period])) {
                $periodScores[$period] = [];
            }
            $periodScores[$period][] = $score->score;
        }

        $periodAverages = [];
        foreach ($periodScores as $period => $scores) {
            $periodAverages[$period] = array_sum($scores) / count($scores);
        }

        return view('customer-satisfaction.my-scores', compact('myScores', 'average', 'totalScores', 'periodAverages'));
    }

    public function employeeScores($employeeId)
    {
        $scores = CustomerSatisfactionScore::with('ratedBy')
            ->where('employee_id', $employeeId)
            ->latest()
            ->get();

        return response()->json($scores);
    }
}
