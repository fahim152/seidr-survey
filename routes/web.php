<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\QuestionController;
use Illuminate\Support\Facades\Route;

// Static client-side questions (No backend logic for step-by-step questions)
Route::view('/survey', 'question')->name('survey');

// Thank-you page after submission
Route::get('/thank-you', [QuestionController::class, 'thankYou'])->name('thank-you');

// Store responses (single endpoint for client-side submission)
Route::post('/submit-survey', [QuestionController::class, 'store'])->name('survey.store');
Route::get('/api/questions', [QuestionController::class, 'fetchQuestions'])->name('questions.fetch');
// Authentication Routes
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Protected Routes for Admin
Route::middleware(['auth'])->group(function () {
    Route::get('/responses', [QuestionController::class, 'showAllResponses'])->name('responses.index');
    Route::get('/export-responses', [QuestionController::class, 'exportResponses'])->name('responses.export');
});
