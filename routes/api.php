<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\KindController;
use App\Http\Controllers\SpeedController;
use App\Http\Controllers\QuestionController;

// Getting all Kinds
Route::get('kinds', [KindController::class, 'index']);
// Getting all Speeds
Route::get('speeds', [SpeedController::class, 'index']);
// Getting Random Questions
Route::post('questions', [QuestionController::class, 'index']);