<?php

use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\QuestionController;

Route::get('/questions/{step?}', [QuestionController::class, 'index'])->name('questions');
Route::post('/questions/{step}', [QuestionController::class, 'store']);
Route::get('/thank-you', [QuestionController::class, 'thankYou'])->name('thank-you');

// Authentication Routes
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Protected Routes
Route::middleware(['auth'])->group(function () {
    Route::get('/responses', [QuestionController::class, 'showAllResponses'])->name('responses.index');
    Route::get('/export-responses', [QuestionController::class, 'exportResponses'])->name('responses.export');
});
