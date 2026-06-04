<?php

namespace App\Http\Controllers;

use App\Models\KpiReport;
use App\Models\KpiTarget;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class KpiReportController extends Controller
{
    public function index(Request $request)
    {
        $query = KpiReport::with(['kpiTarget.employee', 'reportedBy']);

        if ($request->has('kpi_target_id')) {
            $query->where('kpi_target_id', $request->kpi_target_id);
        }

        if (Auth::user()->role->slug === 'employee') {
            $query->where('reported_by', Auth::id());
        }

        $reports = $query->latest()->get();

        return response()->json($reports);
    }

    public function indexView(Request $request)
    {
        $employee = Auth::user()->employee;

        if (! $employee) {
            return redirect()->route('dashboard')->with('error', 'Data karyawan tidak ditemukan!');
        }

        $myTargets = KpiTarget::with(['reports' => function ($q) {
            $q->latest();
        }])
            ->where('employee_id', $employee->id)
            ->where('status', 'active')
            ->latest()
            ->get();

        $myReports = KpiReport::with(['kpiTarget.employee', 'reportedBy'])
            ->whereHas('kpiTarget', function ($q) use ($employee) {
                $q->where('employee_id', $employee->id);
            })
            ->latest()
            ->get();

        return view('kpi-reports.index', compact('myTargets', 'myReports'));
    }

    public function createView(KpiTarget $kpiTarget)
    {
        $kpiTarget->load(['employee.user', 'reports']);

        // Check if this target belongs to the current user
        if ($kpiTarget->employee_id !== Auth::user()->employee?->id) {
            return redirect()->route('kpi-reports.index')->with('error', 'Anda tidak memiliki akses ke target ini!');
        }

        return view('kpi-reports.create', compact('kpiTarget'));
    }

    public function editView(KpiReport $kpiReport)
    {
        $kpiReport->load(['kpiTarget.employee', 'reportedBy']);

        // Check if this report belongs to the current user
        if ($kpiReport->reported_by !== Auth::id()) {
            return redirect()->route('kpi-reports.index')->with('error', 'Anda tidak memiliki akses ke laporan ini!');
        }

        return view('kpi-reports.edit', compact('kpiReport'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'kpi_target_id' => 'required|exists:kpi_targets,id',
            'actual_value' => 'required|numeric|min:0',
            'notes' => 'nullable|string',
            'report_date' => 'required|date',
        ]);

        $validated['reported_by'] = Auth::id();

        KpiReport::create($validated);

        return redirect()->route('kpi-reports.index')->with('success', 'Laporan KPI berhasil dikirim!');
    }

    public function show(KpiReport $kpiReport)
    {
        $kpiReport->load(['kpiTarget.employee', 'reportedBy']);

        return response()->json($kpiReport);
    }

    public function update(Request $request, KpiReport $kpiReport)
    {
        if ($kpiReport->reported_by !== Auth::id()) {
            return redirect()->route('kpi-reports.index')->with('error', 'Anda tidak memiliki akses ke laporan ini!');
        }

        $validated = $request->validate([
            'actual_value' => 'sometimes|numeric|min:0',
            'notes' => 'nullable|string',
            'report_date' => 'sometimes|date',
        ]);

        $kpiReport->update($validated);

        return redirect()->route('kpi-reports.index')->with('success', 'Laporan KPI berhasil diperbarui!');
    }

    public function destroy(KpiReport $kpiReport)
    {
        if ($kpiReport->reported_by !== Auth::id()) {
            return redirect()->route('kpi-reports.index')->with('error', 'Anda tidak memiliki akses ke laporan ini!');
        }

        $kpiReport->delete();

        return redirect()->route('kpi-reports.index')->with('success', 'Laporan KPI berhasil dihapus!');
    }
}
