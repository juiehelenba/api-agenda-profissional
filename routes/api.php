<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\HorarioController;
use Illuminate\Support\Facades\Route;

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login'])->middleware('throttle:5,1');

Route::middleware('jwt')->group(function () {
    Route::get('/me', [AuthController::class, 'me']);
    Route::apiResource('horarios', HorarioController::class);
});
