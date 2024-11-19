<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\QuestionController;
use App\Http\Controllers\SurveyController;

Route::get('/questions/{step?}', [QuestionController::class, 'index'])->name('questions');
Route::post('/questions/{step}', [QuestionController::class, 'store']);
Route::get('/thank-you', [QuestionController::class, 'thankYou'])->name('thank-you');
Route::get('/responses', [QuestionController::class, 'showAllResponses'])->name('responses.index');

Route::get('/export-responses', [QuestionController::class, 'exportResponses'])->name('responses.export');

