<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Spatie\Permission\Models\Role;

class AdminDashboardController extends Controller
{
     public function index()
    {
        $roles = Role::withCount('users')->get();
        return view('admin.dashboard', compact('roles'));
    }
}