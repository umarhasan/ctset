<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Semester;
use App\Models\Rotation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Storage;
use DB;

class UserController extends Controller
{
    /* ===================== INDEX ===================== */
    public function index()
    {
        $users = User::with('roles')->get();
        return view('admin.users.index', compact('users'));
    }

    /* ===================== CREATE ===================== */
    public function create()
    {
        $roles = Role::pluck('name','name')->all();
        $semesters = Semester::get();
        $rotations = Rotation::get();
        return view('admin.users.create', compact('roles','semesters','rotations'));
    }

    /* ===================== STORE ===================== */
    public function store(Request $request)
    {
        $request->validate([
            'fname' => 'required|string|max:255',
            'lname' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|confirmed|min:6',
            'roles' => 'required|array',
            'gender' => 'nullable|in:1,2',
            'sub_utype' => 'nullable|in:1,2',
            'sem_id' => 'nullable|exists:semesters,id',
            'rotation_id' => 'nullable|exists:rotations,id',
            'profile_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'signature_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        DB::transaction(function() use ($request) {
            // Create user
            $user = User::create([
                'name' => $request->fname . ' ' . $request->lname,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'gender' => $request->gender,
                'sub_utype' => $request->sub_utype,
                'sem_id' => $request->sem_id,
                'rotation_id' => $request->rotation_id,
            ]);

            // Profile Image
            if ($request->hasFile('profile_image')) {
                $file = $request->file('profile_image');
                $filename = 'profile_'.$user->id.'_'.time().'.'.$file->getClientOriginalExtension();
                $file->storeAs('profiles', $filename, 'public');
                $user->profile_image = $filename;
            }

            // Signature Image
            if ($request->hasFile('signature_image')) {
                $file = $request->file('signature_image');
                $filename = 'signature_'.$user->id.'_'.time().'.'.$file->getClientOriginalExtension();
                $file->storeAs('signatures', $filename, 'public');
                $user->signature_image = $filename;
            }

            $user->save();

            // Assign Roles
            $user->assignRole($request->roles);
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
        $roles = Role::pluck('name','name')->all();
        $userRole = $user->roles->pluck('name','name')->all();
        $semesters = Semester::get();
        $rotations = Rotation::get();
        
        return view('admin.users.edit', compact('user','roles','userRole','semesters','rotations'));
    }

    /* ===================== UPDATE ===================== */
    public function update(Request $request, User $user)
    {
        $request->validate([
            'fname' => 'required|string|max:255',
            'lname' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,'.$user->id,
            'password' => 'nullable|confirmed|min:6',
            'roles' => 'required|array',
            'gender' => 'nullable|in:1,2',
            'sub_utype' => 'nullable|in:1,2',
            'sem_id' => 'nullable|exists:semesters,id',
            'rotation_id' => 'nullable|exists:rotations,id',
            'profile_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'signature_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        DB::transaction(function() use ($request, $user) {
            $user->update([
                'name' => $request->fname . ' ' . $request->lname,
                'email' => $request->email,
                'gender' => $request->gender,
                'sub_utype' => $request->sub_utype,
                'sem_id' => $request->sem_id,
                'rotation_id' => $request->rotation_id,
            ]);

            // Password
            if ($request->filled('password')) {
                $user->password = Hash::make($request->password);
            }

            // Profile Image
            if ($request->hasFile('profile_image')) {
                if ($user->profile_image && Storage::disk('public')->exists('profiles/'.$user->profile_image)) {
                    Storage::disk('public')->delete('profiles/'.$user->profile_image);
                }
                $file = $request->file('profile_image');
                $filename = 'profile_'.$user->id.'_'.time().'.'.$file->getClientOriginalExtension();
                $file->storeAs('profiles', $filename, 'public');
                $user->profile_image = $filename;
            }

            // Signature Image
            if ($request->hasFile('signature_image')) {
                if ($user->signature_image && Storage::disk('public')->exists('signatures/'.$user->signature_image)) {
                    Storage::disk('public')->delete('signatures/'.$user->signature_image);
                }
                $file = $request->file('signature_image');
                $filename = 'signature_'.$user->id.'_'.time().'.'.$file->getClientOriginalExtension();
                $file->storeAs('signatures', $filename, 'public');
                $user->signature_image = $filename;
            }

            $user->save();

            // Sync roles
            $user->syncRoles($request->roles);
        });

        return redirect()->route('users.index')->with('success', 'User updated successfully');
    }

    /* ===================== DELETE ===================== */
    public function destroy(User $user)
    {
        // Delete images
        if ($user->profile_image && Storage::disk('public')->exists('profiles/'.$user->profile_image)) {
            Storage::disk('public')->delete('profiles/'.$user->profile_image);
        }
        if ($user->signature_image && Storage::disk('public')->exists('signatures/'.$user->signature_image)) {
            Storage::disk('public')->delete('signatures/'.$user->signature_image);
        }

        $user->delete();
        return redirect()->route('users.index')->with('success', 'User deleted successfully');
    }
}