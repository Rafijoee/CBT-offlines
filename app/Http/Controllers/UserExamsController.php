<?php

namespace App\Http\Controllers;

use App\Models\Exams;
use App\Models\UserExam;
use Illuminate\Http\Request;

class UserExamsController extends Controller
{
    public function index()
    {
        //
    }

    public function create()
    {
        return view('user_exams.create');
    }

    public function store(Request $request)
    {
        
    }

    public function edit(Exams $exams)
    {
        // PAKAI exam_id (bukan exams_id)
        dd($exams);
        

        return view('user_exams.edit', compact('userExam', 'exam'));
    }

    public function update(Request $request, UserExam $userExam)
    {
        //
    }

    public function destroy(UserExam $userExam)
    {
        //
    }
}
