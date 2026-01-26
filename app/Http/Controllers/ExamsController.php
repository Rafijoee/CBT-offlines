<?php

namespace App\Http\Controllers;

use App\Models\Exams;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

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
            'soal' => 'required',
            'time' => 'required|integer',
            'opened_time' => 'required|date',
            'closed_time' => 'required|date',
        ]);
        $validate_data['token'] = strtoupper(Str::random(6));
        
        Exams::create($validate_data);

        return redirect ('dashboard-guru')->with('status', 'Exam created successfully!');
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

    public function generateToken(Exams $exams)
    {
        $exams->token = strtoupper(Str::random(6));
        $exams->save();

        return redirect()->back()->with('status', 'Token generated successfully!'); 
    }
}
