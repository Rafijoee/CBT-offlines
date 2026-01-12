<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController as Controller;
use App\Http\Controllers\DashboardController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('login', [AuthController::class, 'login'])->name('login');
Route::post('login', [AuthController::class, 'authenticate']);

Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

