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
    return redirect()->route('login');
});

Route::get('/tes', [AuthController::class, 'tes']);
Route::get('login', [AuthController::class, 'login'])->name('login');
Route::post('login', [AuthController::class, 'authenticate']);
Route::post('logout', [AuthController::class, 'logout'])->name('logout');

//role siswa
Route::middleware(['auth', 'role:user'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/hasil-exam/{userExam}', [UserExamsController::class, 'hasil'])->name('exam.hasil');
    Route::post('/exam/save', [UserExamsController::class, 'save'])
        ->name('exam.save');
    Route::post('/examtoken/{id}', [UserAnswerController::class, 'uptoken'])
        ->name('exam.checkToken');
    Route::post('/exam-reset-status/{id}', [UserAnswerController::class, 'resetStatus'])
        ->name('exam.resetStatus');
    Route::post('/exam/{userExam}/finish', [UserExamsController::class, 'finish'])
        ->name('exam.finish');
    Route::post('/exam/violation', [UserExamsController::class, 'reportViolation'])
        ->name('exam.reportViolation');
    Route::get('/exam/blocked/{id}', function ($id) {
        return view('exams.blocked', ['id' => $id]);
    })->name('exam.blocked');
});

//role-guru
Route::middleware(['auth', 'role:guru'])->group(function () {
    Route::get('/dashboard-guru', [GuruDashboardController::class, 'index'])->name('dashboard-guru');
    Route::get('/dashboard-guru/siswa', [AuthController::class, 'index'])->name('siswa.index');
    Route::get('/dashboard-guru/siswa/create', [AuthController::class, 'create'])->name('siswa.create');
    Route::post('/dashboard-guru/siswa/store', [AuthController::class, 'store'])->name('siswa.store');
    Route::get('/dashboard-guru/siswa/{siswa}/edit', [AuthController::class, 'edit'])->name('siswa.edit');
    Route::patch('/dashboard-guru/siswa/{siswa}/update', [AuthController::class, 'update'])->name('siswa.update');
    Route::delete('/dashboard-guru/siswa/{siswa}/delete', [AuthController::class, 'destroyUser'])->name('siswa.destroy');
    Route::post('/create-exams', [ExamsController::class, 'store'])->name('store-exams');
    Route::patch('/refresh-token/{exams}', [ExamsController::class, 'generateToken'])->name('generate-token');
    Route::delete('/delete-exams/{exams}', [ExamsController::class, 'destroy'])->name('delete-exams');
    Route::post('/store-manual-bank-soal/{exams}', [BankSoalController::class, 'store'])->name('store-manual-bank-soal');
    Route::patch('/update-bank-soal/{bankSoal}', [BankSoalController::class, 'update'])->name('update-bank-soal');
    Route::get('/create-bank-soal/{exams}', [BankSoalController::class, 'create'])->name('create-bank-soal');
    Route::post('/submit-bank-soal/{exams}', [BankSoalController::class, 'submitBank'])->name('submit-bank-soal');
    Route::get('/exam/{id}/preview', [BankSoalController::class, 'preview'])
        ->name('bank-soal.preview');
    Route::get('/nilai', [NilaiController::class, 'index'])->name('nilai.index');
    Route::get('/exam/{userExam}/detail', [NilaiController::class, 'detail'])->name('exam.detail');
    Route::get('/nilai/download-result', [NilaiController::class, 'downloadResult'])
        ->name('nilai.download-result');
    Route::post('/sync/user-exam', [NilaiController::class, 'syncUserExam']);
    Route::post('/sync-server', [NilaiController::class, 'syncToServer'])
        ->name('sync.server');
});


Route::post('/import/{session}/finalize', function ($sessionId) {
    return "FINALIZE LOGIC HERE";
})->name('import.finalize');


//User Exams
Route::get('/create-user-exams', [UserExamsController::class, 'create'])->name('create-user-exams');
Route::middleware(['check.exam'])->group(function () {
    Route::get('/create-user-exams/{exams}/{bankSoal?}',[UserExamsController::class, 'edit'])->name('exam.show');
    Route::get('/masuk-exam/{exams}', [UserExamsController::class, 'masuk'])->name('exam.masuk');
});


Route::get('/exam/{id}/download', [UserExamsController::class, 'downloadExam'])
    ->name('exam.download');
Route::post('/import-exam-zip', [UserExamsController::class, 'importZip'])
    ->name('exam.import.zip');
Route::get('/exam/pantau/{exam}', [ExamsController::class, 'pantauExam'])
    ->name('exam.pantau');



Route::post('/import/upload', [ImportSoalController::class, 'upload'])
    ->name('import.upload');

Route::get('/import/{session}/preview', [ImportPreviewController::class, 'show'])
    ->name('import.preview');

//nilai-guru