<?php

namespace App\Http\Controllers;

use App\Models\Exams;
use App\Models\bankSoal;
use App\Models\ImportAnswer;
use App\Models\ImportQuestion;
use Illuminate\Http\Request;
use App\Models\ImportSession;

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
        $questions = BankSoal::where('exams_id', $exam->id)->with('answers')->get();
        $sessionsId = ImportSession::where('exam_id', $examId)->pluck('id')->toArray();
        $importedQuestions = ImportQuestion::whereIn('import_session_id', $sessionsId)->with('answers')->get();
        // dd($importedQuestions);

        return view('bank-soal.create', compact('exam', 'questions', 'importedQuestions'));
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
