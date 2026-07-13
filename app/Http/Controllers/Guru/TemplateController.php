<?php

namespace App\Http\Controllers\Guru;

use App\Http\Controllers\Controller;

class TemplateController extends Controller
{
    public function download()
    {
        $path = storage_path('app/private/template-soal.zip');

        if (!file_exists($path)) {
            abort(404, 'Template tidak ditemukan.');
        }

        return response()->download($path, 'Template-Soal.zip');
    }
}