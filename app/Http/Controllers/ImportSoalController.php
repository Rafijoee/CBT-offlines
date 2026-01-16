<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\SoalImport\ImportParserService;

class ImportSoalController extends Controller
{
public function upload(Request $request)
{  
    $request->validate([
        'exam_id' => 'required|exists:exams,id',
        'file' => 'required|mimes:zip|max:20480',
    ]);
    $session = app(ImportParserService::class)
        ->handle($request->file('file'), $request->exam_id, auth()->id());
    return redirect()->route('import.preview', $session->id);
}

}

