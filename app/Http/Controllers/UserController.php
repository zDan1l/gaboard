<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('role:hr_manager')->only(['create', 'store', 'edit', 'update', 'destroy']);
    }

    /**
     * Display list of users
     */
    public function index()
    {
        $user = Auth::user();

        if ($user->role->slug !== 'hr_manager') {
            abort(403, 'Unauthorized access.');
        }

        $users = User::with(['role', 'employee.department'])
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return view('users.index', compact('users'));
    }

    /**
     * Show the form for creating a new user
     */
    public function create()
    {
        $roles = Role::all();
        return view('users.create', compact('roles'));
    }

    /**
     * Store a newly created user
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'role_id' => 'required|exists:roles,id',
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role_id' => $validated['role_id'],
        ]);

        return redirect()->route('users.index')
            ->with('success', 'User berhasil dibuat.');
    }

    /**
     * Display the specified user
     */
    public function show(User $user)
    {
        $this->authorizeView($user);

        $user->load(['role', 'employee.department', 'employee.manager']);

        return view('users.show', compact('user'));
    }

    /**
     * Show the form for editing the specified user
     */
    public function edit(User $user)
    {
        $this->authorizeEdit($user);

        $roles = Role::all();
        return view('users.edit', compact('user', 'roles'));
    }

    /**
     * Update the specified user
     */
    public function update(Request $request, User $user)
    {
        $this->authorizeEdit($user);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => [
                'required',
                'string',
                'email',
                'max:255',
                Rule::unique('users')->ignore($user->id),
            ],
            'password' => 'nullable|string|min:8|confirmed',
            'role_id' => 'required|exists:roles,id',
        ]);

        $updateData = [
            'name' => $validated['name'],
            'email' => $validated['email'],
            'role_id' => $validated['role_id'],
        ];

        if (!empty($validated['password'])) {
            $updateData['password'] = Hash::make($validated['password']);
        }

        $user->update($updateData);

        return redirect()->route('users.show', $user)
            ->with('success', 'User berhasil diperbarui.');
    }

    /**
     * Remove the specified user
     */
    public function destroy(User $user)
    {
        $this->authorizeEdit($user);

        if ($user->id === Auth::id()) {
            return redirect()->route('users.index')
                ->with('error', 'Anda tidak dapat menghapus akun sendiri.');
        }

        $user->delete();

        return redirect()->route('users.index')
            ->with('success', 'User berhasil dihapus.');
    }

    /**
     * Authorize user to view
     */
    protected function authorizeView(User $targetUser): void
    {
        $currentUser = Auth::user();

        if ($currentUser->role->slug === 'employee' &&
            $targetUser->id !== $currentUser->id) {
            abort(403, 'Unauthorized access.');
        }
    }

    /**
     * Authorize user to edit
     */
    protected function authorizeEdit(User $targetUser): void
    {
        $currentUser = Auth::user();

        if ($currentUser->role->slug !== 'hr') {
            abort(403, 'Unauthorized access.');
        }
    }
}