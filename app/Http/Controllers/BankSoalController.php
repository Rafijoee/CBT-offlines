<?php

namespace App\Http\Controllers;

use App\Models\Answer;
use App\Models\Exams;
use App\Models\bankSoal;
use App\Models\BankSoal as ModelsBankSoal;
use App\Models\ImportAnswer;
use App\Models\ImportQuestion;
use Illuminate\Http\Request;
use App\Models\ImportSession;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;


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
        $questions = bankSoal::where('exams_id', $exam->id)->with('answers')->get();
        $sessionsId = ImportSession::where('exam_id', $examId)->pluck('id')->toArray();
        $importedQuestions = ImportQuestion::whereIn('import_session_id', $sessionsId)->with('answers')->get();

        return view('bank-soal.create', compact('exam', 'questions', 'importedQuestions'));
    }
    public function preview($examId)
    {
        $exam = Exams::findOrFail($examId);
        $questions = bankSoal::where('exams_id', $exam->id)->with('answers')->get();

        return view('bank-soal.preview', compact('exam', 'questions'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store($examId, Request $request)
    {
    $request->validate([
        'question_text' => 'required|string',
        'question_image' => 'nullable|image|max:2048',
        'answers' => 'required|array|size:4',
        'answers.*.text' => 'required|string',
        'answers.*.image' => 'nullable|image|max:2048',
        'correct_answer' => 'required|in:A,B,C,D',
    ]);

    DB::transaction(function () use ($request, $examId) {


        $session = ImportSession::firstOrCreate([
            'exam_id' => $examId,
            'user_id' => auth()->id(),
            'status'  => 'draft',
        ], [
            'original_file' => 'manual',
        ]);

        $questionImagePath = null;
        if ($request->hasFile('question_image')) {
            $questionImagePath = $request->file('question_image')
                ->store("imports/{$session->id}", 'public');
        }

        $question = ImportQuestion::create([
            'import_session_id' => $session->id,
            'question_text'     => $request->question_text,
            'question_image'    => $questionImagePath,
            'score'             => 1,
            'type'              => 'PG',
            'order_no'          => ImportQuestion::where('import_session_id', $session->id)->count() + 1,
        ]);

        foreach ($request->answers as $key => $answer) {

            $answerImagePath = null;
            if (isset($answer['image'])) {
                $answerImagePath = $answer['image']
                    ->store("imports/{$session->id}", 'public');
            }

            ImportAnswer::create([
                'import_question_id' => $question->id,
                'option_key'         => $key,
                'answer_text'        => $answer['text'],
                'answer_image'       => $answerImagePath,
                'is_true'            => $request->correct_answer === $key,
            ]);
        }
    });

    return redirect()
        ->back()
        ->with('success', 'Soal berhasil ditambahkan ');
    }

    public function submitBank($examId)
    {
        $exam = Exams::findOrFail($examId);

        /**
         * 1️⃣ AMBIL SESSION DRAFT
         */
        $session = ImportSession::where('exam_id', $exam->id)
            ->where('status', 'draft')
            ->first();
        
        if (!$session) {
            return back()->withErrors('Tidak ada draft soal untuk disubmit.');
        }

        /**
         * 2️⃣ AMBIL SEMUA DRAFT QUESTION
         */
        $draftQuestions = ImportQuestion::with('answers')
            ->where('import_session_id', $session->id)
            ->orderBy('order_no')
            ->get();

        if ($draftQuestions->isEmpty()) {
            return back()->withErrors('Soal masih kosong.');
        }
        /**
         * 3️⃣ VALIDASI FINAL
         */
        foreach ($draftQuestions as $q) {

            if ($q->answers->count() < 2) {
                return back()->withErrors(
                    "Soal nomor {$q->order_no} harus memiliki minimal 2 jawaban."
                );
            }

            if ($q->answers->where('is_true', true)->count() !== 1) {
                return back()->withErrors(
                    "Soal nomor {$q->order_no} harus memiliki tepat 1 jawaban benar."
                );
            }
        }

        // dd('passed validation');

        /**
         * 4️⃣ TRANSACTION COMMIT
         */
        DB::transaction(function () use ($draftQuestions, $exam, $session) {

            foreach ($draftQuestions as $draft) {

                $question = ModelsBankSoal::create([
                    'exams_id'       => $exam->id,
                    'question_text' => $draft->question_text,
                    'gambar'=> $draft->question_image,
                ]);

                foreach ($draft->answers as $a) {
                    Answer::create([
                        'bank_soal_id' => $question->id,
                        'text' => $a->answer_text,
                        'gambar'=> $a->answer_image,
                        'true'     => $a->is_true,
                    ]);
                }
            }

            /**
             * 5️⃣ UPDATE STATUS SESSION
             */
            $session->update(['status' => 'published']);
        });

        /**
         */

        $path = storage_path("app/imports/{$session->id}");
        File::deleteDirectory($path);
        

        return redirect()
            ->route('create-bank-soal', $exam->id)
            ->with('success', 'Soal berhasil disubmit ke bank soal.');
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
