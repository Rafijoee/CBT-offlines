<?php

namespace App\Http\Controllers;

use App\Models\Exams;
use App\Models\UserAnswer;
use App\Models\UserExam;
use Illuminate\Http\Request;

class UserAnswerController extends Controller
{

    public function index()
    {
        
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(UserAnswer $userAnswer)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(UserAnswer $userAnswer)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, UserAnswer $userAnswer)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(UserAnswer $userAnswer)
    {
        //
    }
    public function violation(Request $request)
{
    $userExam = auth()->user()->currentExam;

    $userExam->update([
        'status' => 'blocked'
    ]);

    return response()->json(['message' => 'blocked']);
}  
public function uptoken (Request $request, $id)
    {
        $token = Exams::where('id', $id)->first()->token;
        if (strtoupper($request->token) === strtoupper($token)) {
                return redirect()->route('exam.masuk', $id);
            } else {
                // Jika token salah, kembalikan dengan pesan error
                return back()->with('error', 'Token yang Anda masukkan salah!');
            }
    }

public function resetStatus(Request $request, $id)
{
    $exam = Exams::findOrFail($id);
    $token = $exam->token;
    if (strtoupper($request->code) === strtoupper($token)) {
        UserExam::where('exam_id', $id)->where('user_id', auth()->id())->update(['status' => 'ongoing']);
    return redirect()->route('exam.masuk', ['exams' => $id]);
    } else {
        return response()->json(['message' => 'invalid']);
    }
}

}
