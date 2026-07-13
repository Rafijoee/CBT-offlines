<?php

namespace App\Http\Controllers;

use App\Models\Exams;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Models\UserExam;
use App\Models\User;

class ExamsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {

    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
         
        $validate_data = $request->validate([
            'mapel' => 'required',
            'time' => 'required|integer',
            'kelas' => 'required',
            'opened_time' => 'required|date',
            'closed_time' => 'required|date',
        ]);
        $validate_data['token'] = strtoupper(Str::random(6));
        $validate_data['soal'] = 10;
        
        Exams::create($validate_data);

        return redirect ('dashboard-guru')->with('status', 'Exam created successfully!');
    }

    /**
     * Display the specified resource.
     */
public function pantauExam (Exams $exam)
{
    $students = User::where('role', 'user')
        ->where('kelas', $exam->kelas)
        ->get();

    $userExams = UserExam::where('exam_id', $exam->id)
        ->get()
        ->keyBy('user_id');

    $monitoring = $students->map(function ($student) use ($userExams) {

        $userExam = $userExams->get($student->id);
        if (!$userExam) {
            $status = 'belum';
        } elseif ($userExam->status === 'finished') {
            $status = 'selesai';
        } else {
            $status = 'sedang';
        }

        return [
            'student' => $student,
            'status' => $status,
            'started_at' => $userExam?->started_at,
            'submitted_at' => $userExam?->submitted_at,
        ];
    });

    return view('exams.pantau', [
        'exam' => $exam,
        'monitoring' => $monitoring,
        'totalStudents' => $students->count(),
        'belum' => $monitoring->where('status', 'belum')->count(),
        'sedang' => $monitoring->where('status', 'sedang')->count(),
        'selesai' => $monitoring->where('status', 'selesai')->count(),
    ]);
}

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $exam = Exams::findOrFail($id);
        $exam->delete();

        return redirect()->back()->with('status', 'Exam deleted successfully!');
    }

    public function generateToken(Exams $exams)
    {
        $exams->token = strtoupper(Str::random(6));
        $exams->save();

        return redirect()->back()->with('status', 'Token generated successfully!'); 
    }
    
}
