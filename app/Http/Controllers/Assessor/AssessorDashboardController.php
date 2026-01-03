<?php

namespace App\Http\Controllers\Assessor;

use App\Http\Controllers\Controller;

class AssessorDashboardController extends Controller
{
    public function index()
    {
        return view('assessor.dashboard');
    }
}
