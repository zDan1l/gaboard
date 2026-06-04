<?php

namespace App\Http\Controllers;

use App\Models\AttendanceSchedule;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AttendanceScheduleController extends Controller
{
    public function index(Request $request)
    {
        $query = AttendanceSchedule::with(['createdBy', 'entries.employee']);

        if ($request->has('date')) {
            $query->where('schedule_date', $request->date);
        }

        if ($request->has('month')) {
            $query->whereMonth('schedule_date', $request->month);
        }

        $schedules = $query->latest('schedule_date')->get();

        return response()->json($schedules);
    }

    public function indexView(Request $request)
    {
        $query = AttendanceSchedule::with('createdBy');

        if ($request->has('date') && $request->date) {
            $query->where('schedule_date', $request->date);
        }

        if ($request->has('month') && $request->month) {
            $query->whereMonth('schedule_date', $request->month);
        }

        $schedules = $query->latest('schedule_date')->get();

        $todaySchedule = AttendanceSchedule::where('schedule_date', today())->first();

        return view('attendance-schedules.index', compact('schedules', 'todaySchedule'));
    }

    public function createView()
    {
        return view('attendance-schedules.create');
    }

    public function editView(AttendanceSchedule $attendanceSchedule)
    {
        $attendanceSchedule->load('createdBy');

        return view('attendance-schedules.edit', compact('attendanceSchedule'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'schedule_date' => 'required|date',
            'title' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'is_working_day' => 'nullable|boolean',
        ]);

        $validated['created_by'] = Auth::id();
        $validated['is_working_day'] = $validated['is_working_day'] ?? true;

        AttendanceSchedule::create($validated);

        return redirect()->route('attendance-schedules.index')->with('success', 'Jadwal absensi berhasil dibuat!');
    }

    public function show(AttendanceSchedule $attendanceSchedule)
    {
        $attendanceSchedule->load(['createdBy', 'entries.employee']);

        return response()->json($attendanceSchedule);
    }

    public function update(Request $request, AttendanceSchedule $attendanceSchedule)
    {
        $validated = $request->validate([
            'title' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'is_working_day' => 'nullable|boolean',
        ]);

        $attendanceSchedule->update($validated);

        return redirect()->route('attendance-schedules.index')->with('success', 'Jadwal absensi berhasil diperbarui!');
    }

    public function destroy(AttendanceSchedule $attendanceSchedule)
    {
        $attendanceSchedule->delete();

        return redirect()->route('attendance-schedules.index')->with('success', 'Jadwal absensi berhasil dihapus!');
    }

    public function today()
    {
        $schedule = AttendanceSchedule::with(['entries' => function ($query) {
            $query->where('employee_id', Auth::user()?->employee?->id);
        }])
            ->where('schedule_date', today())
            ->first();

        if (! $schedule) {
            return response()->json(['message' => 'No schedule for today'], 404);
        }

        return response()->json($schedule);
    }
}
