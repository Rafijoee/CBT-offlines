<?php

namespace App\Http\Controllers;
use App\Models\ImportSession;


use Illuminate\Http\Request;

class ImportPreviewController extends Controller
{
    public function show(ImportSession $session)
    {
        abort_if($session->user_id !== auth()->id(), 403);
        

        $session->load('questions.answers');
        return view('import.preview', compact('session'));
    }
}
