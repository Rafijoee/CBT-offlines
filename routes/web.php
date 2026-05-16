<?php

use App\Models\ImportSession;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ExamsController;
use App\Http\Controllers\BankSoalController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\UserExamsController;
use App\Http\Controllers\ImportSoalController;
use App\Http\Controllers\ImportPreviewController;
use App\Http\Controllers\DashboardController as Controller;
use App\Http\Controllers\Guru\DashboardController as GuruDashboardController;
use App\Http\Controllers\Guru\NilaiController;
use App\Http\Controllers\UserAnswerController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/tes', [AuthController::class, 'tes']);
Route::get('login', [AuthController::class, 'login'])->name('login');
Route::post('login', [AuthController::class, 'authenticate']);
Route::post('logout', [AuthController::class, 'logout'])->name('logout');

//dashboard
Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
Route::get('/dashboard-guru', [GuruDashboardController::class, 'index'])->name('dashboard-guru');

//Exams
Route::post('/create-exams', [ExamsController::class, 'store'])->name('store-exams');
Route::patch('/refresh-token/{exams}', [ExamsController::class, 'generateToken'])->name('generate-token');
Route::delete('/delete-exams/{exams}', [ExamsController::class, 'destroy'])->name('delete-exams');

//Bank Soal
Route::get('/create-bank-soal/{exams}', [BankSoalController::class, 'create'])->name('create-bank-soal');
Route::post('/store-manual-bank-soal/{exams}', [BankSoalController::class, 'store'])->name('store-manual-bank-soal');
Route::patch('/update-bank-soal/{bankSoal}', [BankSoalController::class, 'update'])->name('update-bank-soal');
// Route::edit('/edit-bank-soal/{bankSoal}', [BankSoalController::class, 'edit'])->name('edit-bank-soal');
// Route::delete('/delete-bank-soal/{bankSoal}', [BankSoalController::class, 'destroy'])->name('delete-bank-soal');
Route::post('/submit-bank-soal/{exams}', [BankSoalController::class, 'submitBank'])->name('submit-bank-soal');
Route::get('/exam/{id}/preview', [BankSoalController::class, 'preview'])
    ->name('bank-soal.preview');

//User Exams
// Route::get('/create-user-exams', [UserExamsController::class, 'create'])->name('create-user-exams');
Route::middleware(['check.exam'])->group(function () {
    Route::get('/create-user-exams/{exams}/{bankSoal?}',[UserExamsController::class, 'edit'])->name('exam.show');
    Route::get('/masuk-exam/{exams}', [UserExamsController::class, 'masuk'])->name('exam.masuk');
});
    
Route::get('/hasil-exam/{userExam}', [UserExamsController::class, 'hasil'])->name('exam.hasil');
Route::post('/exam/save', [UserExamsController::class, 'save'])
    ->name('exam.save');
Route::post('/examtoken/{id}', [UserAnswerController::class, 'uptoken'])
    ->name('exam.checkToken');
Route::post('/exam-reset-status/{id}', [UserAnswerController::class, 'resetStatus'])
    ->name('exam.resetStatus');
Route::post('/exam/{userExam}/finish', [UserExamsController::class, 'finish'])
    ->name('exam.finish');
Route::get('/exam/{id}/download', [UserExamsController::class, 'downloadExam'])
    ->name('exam.download');
Route::post('/import-exam-zip', [UserExamsController::class, 'importZip'])
    ->name('exam.import.zip');
Route::post('/exam/violation', [UserExamsController::class, 'reportViolation'])
    ->name('exam.reportViolation');
Route::get('/exam/blocked/{id}', function ($id) {
    return view('exams.blocked', ['id' => $id]);
})->name('exam.blocked');



Route::post('/import/upload', [ImportSoalController::class, 'upload'])
    ->name('import.upload');

Route::get('/import/{session}/preview', [ImportPreviewController::class, 'show'])
    ->name('import.preview');

//nilai-guru
Route::get('/nilai', [NilaiController::class, 'index'])->name('nilai.index');
Route::get('/exam/{userExam}/detail', [NilaiController::class, 'detail'])->name('exam.detail');
Route::get('/nilai/download-result', [NilaiController::class, 'downloadResult'])
    ->name('nilai.download-result');
Route::post('/sync/user-exam', [NilaiController::class, 'syncUserExam']);
Route::post('/sync-server', [NilaiController::class, 'syncToServer'])
    ->name('sync.server');


Route::post('/import/{session}/finalize', function ($sessionId) {
    return "FINALIZE LOGIC HERE";
})->name('import.finalize');




