<?php

namespace App\Http\Controllers;

use App\Models\ImportSession;
use Illuminate\Http\Request;
use App\Services\SoalImport\ImportParserService;


class ImportSoalController extends Controller
{
public function upload(Request $request)
{  
    $exam_id = $request->input('exam_id');
    $count = ImportSession::where('exam_id', $exam_id)->where('original_file', 'soal.zip')->where('status', 'draft')->count();
    if($count > 1) {
        return redirect()->route('create-bank-soal', $exam_id)
            ->withErrors('Masih ada sesi impor soal yang berstatus draft. Silakan selesaikan submit soal terlebih dahulu sebelum mengunggah yang baru.');
    }
    // dd($request->all());
    $examId = $request->input('exam_id');
    $request->validate([
        'exam_id' => 'required|exists:exams,id',
        'file' => 'required|mimes:zip|max:20480',
    ]);
    $session = app(ImportParserService::class)
        ->handle($request->file('file'), $request->exam_id, auth()->id());
    return redirect()->route('create-bank-soal', $examId)
        ->with('success', 'Soal berhasil ditambahkan !');
}

}

