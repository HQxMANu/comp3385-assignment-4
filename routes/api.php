<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MovieController;
use App\Http\Controllers\AuthController;

Route::post('/v1/login', [AuthController::class, 'login']);


Route::middleware('auth:api')->group(function () {
    Route::post('/v1/logout', [AuthController::class, 'logout']);
    Route::get('/v1/movies', [MovieController::class, 'index']);
    Route::post('/v1/movies', [MovieController::class, 'store']);
});