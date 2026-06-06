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

        // Check for overlapping KPI targets for the same employee
        $employeeId = $validated['employee_id'];
        $startDate = $validated['start_date'];
        $endDate = $validated['end_date'] ?? null;

        $overlapQuery = KpiTarget::where('employee_id', $employeeId)
            ->where('status', 'active')
            ->where(function ($query) use ($startDate, $endDate) {
                if ($endDate) {
                    // Check if new target overlaps with existing targets
                    $query->where(function ($q) use ($startDate, $endDate) {
                        $q->where('start_date', '<=', $endDate)
                            ->whereRaw('COALESCE(end_date, "9999-12-31") >= ?', [$startDate]);
                    });
                } else {
                    // New target has no end date, so check if any existing target overlaps
                    $query->where('start_date', '<=', $startDate)
                        ->whereRaw('COALESCE(end_date, "9999-12-31") >= ?', [$startDate]);
                }
            });

        if ($overlapQuery->exists()) {
            $overlappingTarget = $overlapQuery->first();

            return redirect()->route('kpi-targets.index')
                ->with('error', 'Target KPI untuk karyawan ini bertabrakan dengan target yang sudah ada: "'.$overlappingTarget->title.'" ('.$overlappingTarget->start_date.' s/d '.($overlappingTarget->end_date ?? 'tak terbatas').').');
        }

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

        // Check for overlapping KPI targets if employee, dates, or status is being updated
        if (isset($validated['employee_id']) || isset($validated['start_date']) || isset($validated['end_date']) || isset($validated['status'])) {
            $employeeId = $validated['employee_id'] ?? $kpiTarget->employee_id;
            $startDate = $validated['start_date'] ?? $kpiTarget->start_date;
            $endDate = $validated['end_date'] ?? $kpiTarget->end_date;
            $status = $validated['status'] ?? $kpiTarget->status;

            if ($status === 'active') {
                $overlapQuery = KpiTarget::where('employee_id', $employeeId)
                    ->where('status', 'active')
                    ->where('id', '!=', $kpiTarget->id)
                    ->where(function ($query) use ($startDate, $endDate) {
                        if ($endDate) {
                            $query->where(function ($q) use ($startDate, $endDate) {
                                $q->where('start_date', '<=', $endDate)
                                    ->whereRaw('COALESCE(end_date, "9999-12-31") >= ?', [$startDate]);
                            });
                        } else {
                            $query->where('start_date', '<=', $startDate)
                                ->whereRaw('COALESCE(end_date, "9999-12-31") >= ?', [$startDate]);
                        }
                    });

                if ($overlapQuery->exists()) {
                    $overlappingTarget = $overlapQuery->first();

                    return redirect()->route('kpi-targets.index')
                        ->with('error', 'Perubahan target KPI akan bertabrakan dengan target yang sudah ada: "'.$overlappingTarget->title.'" ('.$overlappingTarget->start_date.' s/d '.($overlappingTarget->end_date ?? 'tak terbatas').').');
                }
            }
        }

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
