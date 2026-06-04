<?php

namespace App\Http\Controllers;

use App\Models\Department;
use App\Models\Employee;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class EmployeeController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('role:hr_manager')->only(['create', 'store', 'edit', 'update', 'destroy']);
    }

    /**
     * Display list of employees
     */
    public function index()
    {
        $user = Auth::user();

        if ($user->role->slug === 'hr_manager') {
            $employees = Employee::with(['user', 'department', 'manager'])
                ->orderBy('created_at', 'desc')
                ->paginate(20);
        } else {
            abort(403, 'Unauthorized access.');
        }

        return view('employees.index', compact('employees'));
    }

    /**
     * Show the form for creating a new employee
     */
    public function create()
    {
        $user = Auth::user();

        $departments = Department::all();
        // Get HR Managers (users with hr_manager role) as potential managers
        $managers = User::whereHas('role', function ($query) {
            $query->where('slug', 'hr_manager');
        })->get();

        // Get users with 'employee' role who don't have Employee records yet
        $users = User::whereHas('role', function ($query) {
            $query->where('slug', 'employee');
        })->doesntHave('employee')->get();

        return view('employees.create', compact('departments', 'managers', 'users'));
    }

    /**
     * Store a newly created employee
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id|unique:employees,user_id',
            'department_id' => 'required|exists:departments,id',
            'manager_id' => 'nullable|exists:users,id',
            'employee_code' => 'required|string|max:255|unique:employees,employee_code',
            'position' => 'required|string|max:255',
            'phone' => 'nullable|string|max:255',
            'join_date' => 'required|date',
            'status' => 'required|in:active,inactive,terminated',
        ]);

        Employee::create($validated);

        return redirect()->route('employees.index')
            ->with('success', 'Karyawan berhasil ditambahkan.');
    }

    /**
     * Display the specified employee
     */
    public function show(Employee $employee)
    {
        $this->authorizeView($employee);

        $employee->load(['user', 'department', 'manager', 'evaluations' => function ($query) {
            $query->orderBy('created_at', 'desc')->limit(10);
        }]);

        return view('employees.show', compact('employee'));
    }

    /**
     * Show the form for editing the specified employee
     */
    public function edit(Employee $employee)
    {
        $this->authorizeEdit($employee);

        $departments = Department::all();
        // Get HR Managers (users with hr_manager role) as potential managers
        $managers = User::whereHas('role', function ($query) {
            $query->where('slug', 'hr_manager');
        })->get();

        return view('employees.edit', compact('employee', 'departments', 'managers'));
    }

    /**
     * Update the specified employee
     */
    public function update(Request $request, Employee $employee)
    {
        $this->authorizeEdit($employee);

        $validated = $request->validate([
            'department_id' => 'required|exists:departments,id',
            'manager_id' => 'nullable|exists:users,id',
            'employee_code' => [
                'required',
                'string',
                'max:255',
                Rule::unique('employees')->ignore($employee->id),
            ],
            'position' => 'required|string|max:255',
            'phone' => 'nullable|string|max:255',
            'join_date' => 'required|date',
            'status' => 'required|in:active,inactive,terminated',
        ]);

        $employee->update($validated);

        return redirect()->route('employees.show', $employee)
            ->with('success', 'Data karyawan berhasil diperbarui.');
    }

    /**
     * Remove the specified employee
     */
    public function destroy(Employee $employee)
    {
        $this->authorizeEdit($employee);

        $employee->delete();

        return redirect()->route('employees.index')
            ->with('success', 'Karyawan berhasil dihapus.');
    }

    /**
     * Authorize user to view employee
     */
    protected function authorizeView(Employee $employee): void
    {
        $currentUser = Auth::user();

        if ($currentUser->role->slug === 'employee' &&
            $employee->user_id !== $currentUser->id) {
            abort(403, 'Unauthorized access.');
        }
    }

    /**
     * Authorize user to edit employee
     */
    protected function authorizeEdit(Employee $employee): void
    {
        $currentUser = Auth::user();

        if ($currentUser->role->slug !== 'hr_manager') {
            abort(403, 'Unauthorized access.');
        }
    }
}
