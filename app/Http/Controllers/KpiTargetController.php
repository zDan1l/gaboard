<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\KpiTarget;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class KpiTargetController extends Controller
{
    public function index(Request $request)
    {
        $query = KpiTarget::with(['employee', 'createdBy', 'reports']);

        if (Auth::user()->role->slug === 'manager') {
            $query->whereHas('employee', function ($q) {
                $q->where('manager_id', Auth::id());
            });
        }

        $kpiTargets = $query->latest()->get();

        return response()->json($kpiTargets);
    }

    public function indexView(Request $request)
    {
        $query = KpiTarget::with(['employee.user', 'createdBy', 'reports']);

        if (Auth::user()->role->slug === 'manager') {
            $query->whereHas('employee', function ($q) {
                $q->where('manager_id', Auth::id());
            });
        }

        // Filter by status
        if ($request->has('status') && $request->status) {
            $query->where('status', $request->status);
        }

        // Filter by period
        if ($request->has('period') && $request->period) {
            $query->where('period', $request->period);
        }

        // Search by employee name
        if ($request->has('search') && $request->search) {
            $query->whereHas('employee.user', function ($q) use ($request) {
                $q->where('name', 'like', '%'.$request->search.'%');
            });
        }

        $kpiTargets = $query->latest()->get();
        // Only get regular employees, not HR Managers
        $employees = Employee::with('user')
            ->whereHas('user.role', function ($query) {
                $query->where('slug', 'employee');
            })->get();

        return view('kpi-targets.index', compact('kpiTargets', 'employees'));
    }

    public function createView()
    {
        // Only get regular employees, not HR Managers
        $employees = Employee::with('user')
            ->whereHas('user.role', function ($query) {
                $query->where('slug', 'employee');
            })->get();

        return view('kpi-targets.create', compact('employees'));
    }

    public function editView(KpiTarget $kpiTarget)
    {
        // Only get regular employees, not HR Managers
        $employees = Employee::with('user')
            ->whereHas('user.role', function ($query) {
                $query->where('slug', 'employee');
            })->get();
        $kpiTarget->load(['employee.user', 'createdBy', 'reports']);

        return view('kpi-targets.edit', compact('kpiTarget', 'employees'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'employee_id' => 'required|exists:employees,id',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'target_value' => 'required|numeric|min:0',
            'unit' => 'nullable|string|max:50',
            'period' => 'required|in:daily,weekly,monthly,custom',
            'start_date' => 'required|date',
            'end_date' => 'nullable|date|after:start_date',
            'status' => 'nullable|in:active,inactive',
        ]);

        $validated['created_by'] = Auth::id();
        $validated['status'] = $validated['status'] ?? 'active';

        KpiTarget::create($validated);

        return redirect()->route('kpi-targets.index')->with('success', 'Target KPI berhasil dibuat!');
    }

    public function show(KpiTarget $kpiTarget)
    {
        $kpiTarget->load(['employee', 'createdBy', 'reports.reportedBy']);

        return response()->json($kpiTarget);
    }

    public function update(Request $request, KpiTarget $kpiTarget)
    {
        $validated = $request->validate([
            'employee_id' => 'sometimes|required|exists:employees,id',
            'title' => 'sometimes|string|max:255',
            'description' => 'nullable|string',
            'target_value' => 'sometimes|numeric|min:0',
            'unit' => 'nullable|string|max:50',
            'period' => 'sometimes|in:daily,weekly,monthly,custom',
            'start_date' => 'sometimes|date',
            'end_date' => 'nullable|date|after:start_date',
            'status' => 'sometimes|in:active,inactive',
        ]);

        $kpiTarget->update($validated);

        return redirect()->route('kpi-targets.index')->with('success', 'Target KPI berhasil diperbarui!');
    }

    public function destroy(KpiTarget $kpiTarget)
    {
        $kpiTarget->delete();

        return redirect()->route('kpi-targets.index')->with('success', 'Target KPI berhasil dihapus!');
    }

    public function myTargets()
    {
        $employee = Auth::user()->employee;

        if (! $employee) {
            return response()->json(['message' => 'Employee record not found'], 404);
        }

        $targets = KpiTarget::with(['reports'])
            ->where('employee_id', $employee->id)
            ->where('status', 'active')
            ->latest()
            ->get();

        return response()->json($targets);
    }
}
