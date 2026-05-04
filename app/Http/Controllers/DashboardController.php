<?php

namespace App\Http\Controllers;

use App\Models\Exams;
use App\Models\UserExam;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = Auth::user();
        $kealas = $user->kelas;
        $exams = Exams::where('kelas', $kealas)->get();
        $idCurrent = UserExam::where('user_id', $user->id)->where('status', 'ongoing')->pluck('exam_id')->toArray();
        $idFinish = UserExam::where('user_id', $user->id)->where('status', 'finished')->pluck('exam_id')->toArray();
        $excludedIds = array_merge($idCurrent, $idFinish);
        // dd($exams, $idCurrent, $idFinish, $excludedIds);

        $currents = Exams::whereIn('id', $idCurrent)->get();
        $finishs = Exams::whereIn('id', $idFinish)->with('userExams')->get();
        $exams = Exams::whereNotIn('id', $excludedIds)->where('kelas', $kealas)->where('opened_time', '<=', now())->get();
        // dd($currents);
        return view('dashboard.siswa', compact('exams', 'currents', 'finishs', 'exams' ));
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
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
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
        //
    }
}
