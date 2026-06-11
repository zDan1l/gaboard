<?php

namespace App\Http\Controllers;

use App\Models\Department;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class DepartmentController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('role:hr_manager')->only(['create', 'store', 'edit', 'update', 'destroy']);
    }

    /**
     * Display list of departments
     */
    public function index()
    {
        $user = Auth::user();

        if ($user->role->slug !== 'hr_manager') {
            abort(403, 'Unauthorized access.');
        }

        $departments = Department::withCount('employees')
            ->with(['employees' => function ($query) {
                $query->where('status', 'active');
            }])
            ->orderBy('name')
            ->paginate(20);

        return view('departments.index', compact('departments'));
    }

    /**
     * Show the form for creating a new department
     */
    public function create()
    {
        return view('departments.create');
    }

    /**
     * Store a newly created department
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:255|unique:departments',
            'location' => 'nullable|string|max:255',
            'description' => 'nullable|string',
        ]);

        Department::create($validated);

        return redirect()->route('departments.index')
            ->with('success', 'Departemen berhasil ditambahkan.');
    }

    /**
     * Display the specified department
     */
    public function show(Department $department)
    {
        $user = Auth::user();

        if ($user->role->slug !== 'hr_manager' && $user->role->slug !== 'executive') {
            abort(403, 'Unauthorized access.');
        }

        $department->load(['employees.user', 'employees.manager']);

        $analytics = [
            'total_employees' => $department->employees->count(),
            'active_employees' => $department->employees->where('status', 'active')->count(),
            'average_performance' => 0,
        ];

        return view('departments.show', compact('department', 'analytics'));
    }

    /**
     * Show the form for editing the specified department
     */
    public function edit(Department $department)
    {
        return view('departments.edit', compact('department'));
    }

    /**
     * Update the specified department
     */
    public function update(Request $request, Department $department)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'code' => [
                'required',
                'string',
                'max:255',
                Rule::unique('departments')->ignore($department->id),
            ],
            'location' => 'nullable|string|max:255',
            'description' => 'nullable|string',
        ]);

        $department->update($validated);

        return redirect()->route('departments.show', $department)
            ->with('success', 'Departemen berhasil diperbarui.');
    }

    /**
     * Remove the specified department
     */
    public function destroy(Department $department)
    {
        if ($department->employees()->count() > 0) {
            return redirect()->route('departments.index')
                ->with('error', 'Tidak dapat menghapus departemen yang masih memiliki karyawan.');
        }

        $department->delete();

        return redirect()->route('departments.index')
            ->with('success', 'Departemen berhasil dihapus.');
    }
}
