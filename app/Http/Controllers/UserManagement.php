<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Role;
use App\Traits\LogsActivity;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserManagement extends Controller
{
    use LogsActivity;

    public function index()
    {
        $users = User::with('role')
            ->when(request('search'), fn($q, $search) =>
                $q->where('first_name', 'like', "%$search%")
                  ->orWhere('last_name',  'like', "%$search%")
                  ->orWhere('username',   'like', "%$search%")
                  ->orWhere('email',      'like', "%$search%")
            )
            ->latest()
            ->paginate(20);

        $roles = Role::all();

        $this->logActivity(
            'Viewed User Management List',
            'User Management',
            'Admin viewed the user management list',
            ['search' => request('search')]
        );

        return view('user-management.index', compact('users', 'roles'));
    }

    public function create()
    {
        $roles = Role::all();

        $this->logActivity(
            'Viewed User Create Form',
            'User Management',
            'Admin opened the user creation form'
        );

        return view('user-management.create', compact('roles'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name'  => 'required|string|max:255',
            'email'      => 'required|email|unique:users,email',
            'username'   => 'required|string|unique:users,username|max:255',
            'password'   => 'required|string|min:8|confirmed',
            'role_id'    => 'required|exists:roles,id',
            'status'     => 'required|in:active,inactive',
        ]);

        $validated['password'] = Hash::make($validated['password']);

        $user = User::create($validated);

        $this->logActivity(
            'Created User',
            'User Management',
            'Admin created a new user: ' . $user->first_name . ' ' . $user->last_name,
            ['user_id' => $user->getKey(), 'username' => $user->username, 'email' => $user->email, 'role_id' => $user->role_id]
        );

        return redirect('/user-management')->with('success', 'User created successfully.');
    }

    public function show(User $userManagement)
    {
        $userManagement->load('role');

        $this->logActivity(
            'Viewed User Profile',
            'User Management',
            'Admin viewed profile of user: ' . $userManagement->first_name . ' ' . $userManagement->last_name,
            ['target_user_id' => $userManagement->getKey(), 'username' => $userManagement->username]
        );

        return view('user-management.show', ['user' => $userManagement]);
    }

    public function edit(User $userManagement)
    {
        $roles = Role::all();

        $this->logActivity(
            'Viewed User Edit Form',
            'User Management',
            'Admin opened the edit form for user: ' . $userManagement->first_name . ' ' . $userManagement->last_name,
            ['target_user_id' => $userManagement->getKey(), 'username' => $userManagement->username]
        );

        return view('user-management.edit', [
            'user'  => $userManagement->load('role'),
            'roles' => $roles,
        ]);
    }

    public function update(Request $request, User $userManagement)
    {
        $validated = $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name'  => 'required|string|max:255',
            'email'      => 'required|email|unique:users,email,' . $userManagement->user_id . ',user_id',
            'username'   => 'required|string|max:255|unique:users,username,' . $userManagement->user_id . ',user_id',
            'password'   => 'nullable|string|confirmed',
            'role_id'    => 'required|exists:roles,id',
            'status'     => 'required|in:active,inactive',
        ]);

        $passwordChanged = ! empty($validated['password']);

        if (! $passwordChanged) {
            unset($validated['password']);
        } else {
            $validated['password'] = Hash::make($validated['password']);
        }

        $userManagement->update($validated);

        $this->logActivity(
            'Updated User',
            'User Management',
            'Admin updated user: ' . $userManagement->first_name . ' ' . $userManagement->last_name,
            [
                'target_user_id'   => $userManagement->getKey(),
                'username'         => $userManagement->username,
                'password_changed' => $passwordChanged,
                'role_id'          => $validated['role_id'],
                'status'           => $validated['status'],
            ]
        );

        return redirect('/user-management')->with('success', 'User updated successfully.');
    }

    public function destroy(User $userManagement)
    {
        if ($userManagement->user_id === auth()->id()) {
            return back()->with('error', 'You cannot delete your own account.');
        }

        $this->logActivity(
            'Deleted User',
            'User Management',
            'Admin deleted user: ' . $userManagement->first_name . ' ' . $userManagement->last_name,
            ['target_user_id' => $userManagement->getKey(), 'username' => $userManagement->username, 'email' => $userManagement->email]
        );

        $userManagement->delete();

        return redirect('/user-management')->with('success', 'User deleted successfully.');
    }
}