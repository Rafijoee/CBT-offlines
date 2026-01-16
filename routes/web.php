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

Route::get('/', function () {
    return view('welcome');
});

Route::get('login', [AuthController::class, 'login'])->name('login');
Route::post('login', [AuthController::class, 'authenticate']);
Route::post('logout', [AuthController::class, 'logout'])->name('logout');

//Exams
Route::get('/exams', [ExamsController::class, 'index'])->name('exams');
Route::get('/create-exams', [ExamsController::class, 'create'])->name('create-exams');
Route::post('/create-exams', [ExamsController::class, 'store'])->name('store-exams');

//Bank Soal
Route::get('/create-bank-soal', [BankSoalController::class, 'create'])->name('create-bank-soal');


Route::post('/create-exams/{exams}', [ExamsController::class, 'check'])->name('check-exams');

//User Exams
Route::get('/user-exams', [UserExamsController::class, 'index'])->name('user-exams');
Route::get('/create-user-exams', [UserExamsController::class, 'create'])->name('create-user-exams');
Route::post('/create-user-exams', [UserExamsController::class, 'store'])->name('store-user-exams');
Route::get('/create-user-exams/{exams}', [UserExamsController::class, 'edit']);

Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

Route::get('/exams/{id}/questions/create', [BankSoalController::class, 'create'])->name('create-question');


Route::post('/import/upload', [ImportSoalController::class, 'upload'])
    ->name('import.upload');

Route::get('/import/{session}/preview', [ImportPreviewController::class, 'show'])
    ->name('import.preview');

Route::post('/import/{session}/finalize', function ($sessionId) {
    return "FINALIZE LOGIC HERE";
})->name('import.finalize');


