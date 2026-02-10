<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\District;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    public function __construct()
    {
        // Super admin and Administrator HQ can access user management
        $this->middleware(function ($request, $next) {
            if (!auth()->user()->isSuperAdmin() && !auth()->user()->isAdministratorHQ()) {
                abort(403, 'Unauthorized access.');
            }
            return $next($request);
        });
    }

    public function index()
    {
        $query = User::with('district');
        
        // Administrator HQ cannot see super_admin users
        if (auth()->user()->isAdministratorHQ() && !auth()->user()->isSuperAdmin()) {
            $query->where('role', '!=', 'super_admin');
        }
        
        $users = $query->orderBy('name')->get();
        $districts = District::where('is_active', true)->orderBy('name')->get();
        return view('users.index', compact('users', 'districts'));
    }

    public function create()
    {
        $districts = District::where('is_active', true)->orderBy('name')->get();
        $institutions = \App\Models\Institution::with('district')->where('is_active', true)->orderBy('name')->get();
        return view('users.create', compact('districts', 'institutions'));
    }

    public function store(Request $request)
    {
        // Administrator HQ can only create district_user
        $allowedRoles = auth()->user()->isSuperAdmin() 
            ? ['super_admin', 'administrator_hq', 'district_user', 'institution']
            : ['district_user'];
        
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'role' => ['required', 'string', Rule::in($allowedRoles)],
            'district_id' => [
                'nullable',
                'exists:districts,id',
                Rule::requiredIf(in_array($request->role, ['district_user', 'institution'])),
            ],
            'institution_id' => [
                'nullable',
                'exists:institutions,id',
                Rule::requiredIf($request->role === 'institution'),
            ],
        ], [
            'district_id.required_if' => 'District is required for district and institution users.',
            'institution_id.required_if' => 'Institution is required for institution users.',
            'role.in' => 'You can only create users with allowed roles.',
        ]);

        $validated['password'] = Hash::make($validated['password']);

        // District should be null for super_admin and administrator_hq
        if (!in_array($validated['role'], ['district_user', 'institution'])) {
            $validated['district_id'] = null;
        }

        // Institution should be set only for institution users
        if ($validated['role'] !== 'institution') {
            $validated['institution_id'] = null;
        }

        User::create($validated);

        return redirect()->route('users.index')
            ->with('success', 'User created successfully.');
    }

    public function show(User $user)
    {
        // Administrator HQ cannot view super_admin users
        if (auth()->user()->isAdministratorHQ() && !auth()->user()->isSuperAdmin() && $user->role === 'super_admin') {
            abort(403, 'You cannot view super admin users.');
        }
        
        $user->load('district');
        return view('users.show', compact('user'));
    }

    public function edit(User $user)
    {
        // Administrator HQ cannot edit super_admin users
        if (auth()->user()->isAdministratorHQ() && !auth()->user()->isSuperAdmin() && $user->role === 'super_admin') {
            abort(403, 'You cannot edit super admin users.');
        }
        
        $districts = District::where('is_active', true)->orderBy('name')->get();
        $institutions = \App\Models\Institution::with('district')->where('is_active', true)->orderBy('name')->get();
        return view('users.edit', compact('user', 'districts', 'institutions'));
    }

    public function update(Request $request, User $user)
    {
        // Administrator HQ cannot edit super_admin users
        if (auth()->user()->isAdministratorHQ() && !auth()->user()->isSuperAdmin() && $user->role === 'super_admin') {
            abort(403, 'You cannot edit super admin users.');
        }
        
        // Administrator HQ can only assign district_user role
        $allowedRoles = auth()->user()->isSuperAdmin() 
            ? ['super_admin', 'administrator_hq', 'district_user', 'institution']
            : ['district_user'];
        
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
            'password' => 'nullable|string|min:8|confirmed',
            'role' => ['required', 'string', Rule::in($allowedRoles)],
            'district_id' => [
                'nullable',
                'exists:districts,id',
                Rule::requiredIf(in_array($request->role, ['district_user', 'institution'])),
            ],
            'institution_id' => [
                'nullable',
                'exists:institutions,id',
                Rule::requiredIf($request->role === 'institution'),
            ],
        ], [
            'district_id.required_if' => 'District is required for district and institution users.',
            'institution_id.required_if' => 'Institution is required for institution users.',
            'role.in' => 'You can only assign allowed roles.',
        ]);

        // Only update password if provided
        if (!empty($validated['password'])) {
            $validated['password'] = Hash::make($validated['password']);
        } else {
            unset($validated['password']);
        }

        // District should be null for super_admin and administrator_hq
        if (!in_array($validated['role'], ['district_user', 'institution'])) {
            $validated['district_id'] = null;
        }

        // Institution should be set only for institution users
        if ($validated['role'] !== 'institution') {
            $validated['institution_id'] = null;
        }

        $user->update($validated);

        return redirect()->route('users.index')
            ->with('success', 'User updated successfully.');
    }

    public function destroy(User $user)
    {
        // Prevent deleting yourself
        if ($user->id === auth()->id()) {
            return redirect()->route('users.index')
                ->with('error', 'You cannot delete your own account.');
        }
        
        // Administrator HQ cannot delete super_admin users
        if (auth()->user()->isAdministratorHQ() && !auth()->user()->isSuperAdmin() && $user->role === 'super_admin') {
            return redirect()->route('users.index')
                ->with('error', 'You cannot delete super admin users.');
        }

        $user->delete();

        return redirect()->route('users.index')
            ->with('success', 'User deleted successfully.');
    }
}

