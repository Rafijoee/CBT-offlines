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

Route::get('/', function () {
    return view('welcome');
});

Route::get('login', [AuthController::class, 'login'])->name('login');
Route::post('login', [AuthController::class, 'authenticate']);
Route::post('logout', [AuthController::class, 'logout'])->name('logout');

//dashboard
Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
Route::get('/dashboard-guru', [GuruDashboardController::class, 'index'])->name('dashboard-guru');

//Exams
Route::post('/create-exams', [ExamsController::class, 'store'])->name('store-exams');
Route::patch('/refresh-token/{exams}', [ExamsController::class, 'generateToken'])->name('generate-token');

//Bank Soal
Route::get('/create-bank-soal/{exams}', [BankSoalController::class, 'create'])->name('create-bank-soal');

//User Exams
Route::get('/create-user-exams', [UserExamsController::class, 'create'])->name('create-user-exams');
Route::post('/create-user-exams', [UserExamsController::class, 'store'])->name('store-user-exams');
Route::get('/create-user-exams/{exams}', [UserExamsController::class, 'edit']);

Route::get('/exams/{id}/questions/create', [BankSoalController::class, 'create'])->name('create-question');
Route::post('/import/upload', [ImportSoalController::class, 'upload'])
    ->name('import.upload');

Route::get('/import/{session}/preview', [ImportPreviewController::class, 'show'])
    ->name('import.preview');

Route::post('/import/{session}/finalize', function ($sessionId) {
    return "FINALIZE LOGIC HERE";
})->name('import.finalize');


