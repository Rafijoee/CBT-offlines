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
        $kelas = $user->kelas;
        $idCurrent = UserExam::where('user_id', $user->id)->where('status', 'ongoing')->pluck('exam_id')->toArray();
        $idFinish = UserExam::where('user_id', $user->id)->where('status', 'finished')->pluck('exam_id')->toArray();
        $excludedIds = array_merge($idCurrent, $idFinish);
        // dd($exams, $idCurrent, $idFinish, $excludedIds);

        $exams = Exams::where('kelas', $kelas)->whereNotIn('id', $excludedIds)->get();
        $currents = Exams::whereIn('id', $idCurrent)->get();
        $finishs = Exams::whereIn('id', $idFinish)->get();
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
