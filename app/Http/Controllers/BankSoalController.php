<?php

namespace App\Http\Controllers;

use App\Models\bankSoal;
use App\Models\Exams;
use Illuminate\Http\Request;

class BankSoalController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create($examId)
    {
        $exam = Exams::findOrFail($examId);
        return view('bank-soal.create', compact('exam'));
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
    public function show(bankSoal $bankSoal)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(bankSoal $bankSoal)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, bankSoal $bankSoal)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(bankSoal $bankSoal)
    {
        //
    }
}
