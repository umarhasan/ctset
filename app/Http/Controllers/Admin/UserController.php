<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\UserInformation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;
use DB;
use Illuminate\Support\Facades\Storage;

class UserController extends Controller
{
    /* ===================== INDEX ===================== */
    public function index()
    {
        $users = User::with('roles')->latest()->paginate(20);
        return view('admin.users.index', compact('users'));
    }

    /* ===================== CREATE ===================== */
    public function create()
    {
        $roles = Role::pluck('name', 'name')->all();
        return view('admin.users.create', compact('roles'));
    }

    /* ===================== STORE ===================== */
    public function store(Request $request)
    {
        $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|unique:users,email',
            'password' => 'required|confirmed|min:6',
            'roles'    => 'required|array',

            // User Information
            'phone'    => 'nullable|string',
            'address'  => 'nullable|string',
        ]);

        DB::transaction(function () use ($request) {

            // User
            $user = User::create([
                'name'     => $request->name,
                'email'    => $request->email,
                'password' => Hash::make($request->password),
            ]);

            // Assign Role
            $user->assignRole($request->roles);

            // User Information
        });

        return redirect()->route('users.index')->with('success', 'User created successfully');
    }

    /* ===================== SHOW ===================== */
    public function show(User $user)
    {
        $user->load('roles');
        return view('admin.users.show', compact('user'));
    }

    /* ===================== EDIT ===================== */
    public function edit(User $user)
    {
        $roles = Role::pluck('name', 'name')->all();
        $userRole = $user->roles->pluck('name', 'name')->all();

        return view('admin.users.edit', compact('user', 'roles', 'userRole'));
    }

    /* ===================== UPDATE ===================== */
    public function update(Request $request, User $user)
    {
        $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|unique:users,email,' . $user->id,
            'password' => 'nullable|confirmed|min:6',
            'roles'    => 'required|array',

            // User Information
            'phone'    => 'nullable|string',
            'address'  => 'nullable|string',
        ]);

        DB::transaction(function () use ($request, $user) {

            // Update user
            $user->update([
                'name'  => $request->name,
                'email' => $request->email,
            ]);

            if ($request->filled('password')) {
                $user->update([
                    'password' => Hash::make($request->password)
                ]);
            }

            // Update roles
            $user->syncRoles($request->roles);

        });

        return redirect()->route('users.index')->with('success', 'User updated successfully');
    }

    public function destroy(User $user)
    {
        $user->delete();
        return redirect()->route('users.index')->with('success', 'User deleted successfully');
    }

}
