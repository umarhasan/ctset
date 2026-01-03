<?php

namespace App\Http\Controllers\Trainee;

use App\Http\Controllers\Controller;

class TraineeDashboardController extends Controller
{
    public function index()
    {
        return view('trainee.dashboard');
    }
}
