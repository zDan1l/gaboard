<?php

namespace App\Http\Controllers;

use App\Models\AttendanceEntry;
use App\Models\AttendanceSchedule;
use App\Models\Employee;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AttendanceEntryController extends Controller
{
    public function index(Request $request)
    {
        $query = AttendanceEntry::with(['schedule', 'employee']);

        if (Auth::user()->role->slug === 'employee') {
            $query->where('employee_id', Auth::user()->employee->id);
        }

        if ($request->has('schedule_id')) {
            $query->where('schedule_id', $request->schedule_id);
        }

        if ($request->has('employee_id')) {
            $query->where('employee_id', $request->employee_id);
        }

        if ($request->has('month')) {
            $query->whereHas('schedule', function ($q) use ($request) {
                $q->whereMonth('schedule_date', $request->month);
            });
        }

        $entries = $query->latest()->get();

        return response()->json($entries);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'schedule_id' => 'required|exists:attendance_schedules,id',
            'employee_id' => 'required|exists:employees,id',
            'clock_in_time' => 'nullable|date',
            'clock_out_time' => 'nullable|date|after:clock_in_time',
            'status' => 'nullable|in:present,late,absent,excused',
            'notes' => 'nullable|string',
        ]);

        $validated['status'] = $validated['status'] ?? 'absent';

        $entry = AttendanceEntry::create($validated);

        return response()->json($entry->load(['schedule', 'employee']), 201);
    }

    public function show(AttendanceEntry $attendanceEntry)
    {
        $attendanceEntry->load(['schedule', 'employee']);

        return response()->json($attendanceEntry);
    }

    public function update(Request $request, AttendanceEntry $attendanceEntry)
    {
        $validated = $request->validate([
            'clock_in_time' => 'nullable|date_format:H:i',
            'clock_out_time' => 'nullable|date_format:H:i',
            'status' => 'nullable|in:present,late,absent,excused',
            'notes' => 'nullable|string',
        ]);

        // Convert time strings to datetime with the schedule's date
        if (isset($validated['clock_in_time']) && $attendanceEntry->schedule) {
            $validated['clock_in_time'] = $attendanceEntry->schedule->schedule_date->setTimeFrom($validated['clock_in_time']);
        }

        if (isset($validated['clock_out_time']) && $attendanceEntry->schedule) {
            $validated['clock_out_time'] = $attendanceEntry->schedule->schedule_date->setTimeFrom($validated['clock_out_time']);
        }

        $attendanceEntry->update($validated);

        // Check if request expects JSON (API) or web (form)
        if ($request->expectsJson()) {
            return response()->json($attendanceEntry->load(['schedule', 'employee']));
        }

        return redirect()->route('attendance-entries.manage')->with('success', 'Absensi berhasil diperbarui!');
    }

    public function destroy(AttendanceEntry $attendanceEntry)
    {
        $attendanceEntry->delete();

        return response()->json(['message' => 'Attendance Entry deleted successfully']);
    }

    public function clockIn(Request $request)
    {
        $employee = Auth::user()->employee;

        if (! $employee) {
            return redirect()->route('attendance-entries.my-attendance')->with('error', 'Data karyawan tidak ditemukan!');
        }

        $schedule = AttendanceSchedule::where('schedule_date', today())->first();

        if (! $schedule) {
            return redirect()->route('attendance-entries.my-attendance')->with('error', 'Tidak ada jadwal absensi untuk hari ini!');
        }

        if (! $schedule->is_working_day) {
            return redirect()->route('attendance-entries.my-attendance')->with('error', 'Hari ini adalah hari libur!');
        }

        $entry = AttendanceEntry::updateOrCreate(
            [
                'schedule_id' => $schedule->id,
                'employee_id' => $employee->id,
            ],
            [
                'clock_in_time' => now(),
                'status' => 'present',
            ]
        );

        return redirect()->route('attendance-entries.my-attendance')->with('success', 'Berhasil absen masuk!');
    }

    public function clockOut(Request $request)
    {
        $employee = Auth::user()->employee;

        if (! $employee) {
            return redirect()->route('attendance-entries.my-attendance')->with('error', 'Data karyawan tidak ditemukan!');
        }

        $schedule = AttendanceSchedule::where('schedule_date', today())->first();

        if (! $schedule) {
            return redirect()->route('attendance-entries.my-attendance')->with('error', 'Tidak ada jadwal absensi untuk hari ini!');
        }

        $entry = AttendanceEntry::where('schedule_id', $schedule->id)
            ->where('employee_id', $employee->id)
            ->first();

        if (! $entry) {
            return redirect()->route('attendance-entries.my-attendance')->with('error', 'Anda belum melakukan absen masuk!');
        }

        $entry->update(['clock_out_time' => now()]);

        return redirect()->route('attendance-entries.my-attendance')->with('success', 'Berhasil absen keluar!');
    }

    public function myAttendance(Request $request)
    {
        $employee = Auth::user()->employee;

        if (! $employee) {
            return response()->json(['message' => 'Employee record not found'], 404);
        }

        $query = AttendanceEntry::with('schedule')
            ->where('employee_id', $employee->id);

        if ($request->has('month')) {
            $query->whereHas('schedule', function ($q) use ($request) {
                $q->whereMonth('schedule_date', $request->month);
            });
        }

        $entries = $query->latest()->get();

        return response()->json($entries);
    }

    public function indexView(Request $request)
    {
        $employee = Auth::user()->employee;

        if (! $employee) {
            return redirect()->route('dashboard')->with('error', 'Data karyawan tidak ditemukan!');
        }

        // Get today's schedule
        $todaySchedule = AttendanceSchedule::where('schedule_date', today())->first();

        // Get today's attendance
        $todayAttendance = null;
        if ($todaySchedule) {
            $todayAttendance = AttendanceEntry::where('schedule_id', $todaySchedule->id)
                ->where('employee_id', $employee->id)
                ->first();
        }

        // Get attendance history
        $query = AttendanceEntry::with('schedule')
            ->where('employee_id', $employee->id);

        if ($request->has('month') && $request->month) {
            $query->whereHas('schedule', function ($q) use ($request) {
                $q->whereMonth('schedule_date', $request->month);
            });
        }

        $attendances = $query->latest()->get();

        // Calculate stats
        $present = $attendances->where('status', 'present')->count();
        $late = $attendances->where('status', 'late')->count();
        $absent = $attendances->where('status', 'absent')->count();
        $excused = $attendances->where('status', 'excused')->count();
        $total = $attendances->count();
        $rate = $total > 0 ? ((($present + $late) / $total) * 100) : 0;

        return view('attendance-entries.index', compact(
            'todaySchedule',
            'todayAttendance',
            'attendances',
            'present',
            'late',
            'absent',
            'excused',
            'total',
            'rate'
        ));
    }

    /**
     * Manager view to manage attendance for all employees
     */
    public function manageView(Request $request)
    {
        if (Auth::user()->role->slug !== 'hr_manager') {
            abort(403, 'Unauthorized access.');
        }

        $query = AttendanceEntry::with(['schedule', 'employee.user']);

        // Filter by date
        if ($request->has('date') && $request->date) {
            $query->whereHas('schedule', function ($q) use ($request) {
                $q->where('schedule_date', $request->date);
            });
        }

        // Filter by employee
        if ($request->has('employee_id') && $request->employee_id) {
            $query->where('employee_id', $request->employee_id);
        }

        // Filter by status
        if ($request->has('status') && $request->status) {
            $query->where('status', $request->status);
        }

        $entries = $query->latest()->get();

        // Get employees for filter dropdown (only regular employees)
        $employees = Employee::with('user')
            ->whereHas('user.role', function ($query) {
                $query->where('slug', 'employee');
            })->get();

        // Get schedules for filter dropdown
        $schedules = AttendanceSchedule::orderBy('schedule_date', 'desc')->limit(30)->get();

        return view('attendance-entries.manage', compact('entries', 'employees', 'schedules'));
    }
}
