<?php

namespace App\Http\Controllers\Guru;

use App\Models\Exams;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class DashboardController extends Controller
{
    function index()
    {
        $exams = Exams::all();
        $now = now();
        return view('dashboard.guru', compact('exams', 'now')  );
    }
}
