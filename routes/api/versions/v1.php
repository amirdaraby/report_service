<?php

use App\Http\Controllers\Api\V1\AuthController;
use App\Http\Controllers\Api\V1\ReportController;
use App\Http\Controllers\Api\V1\UserController;
use Illuminate\Support\Facades\Route;

Route::name('auth.')->prefix('/auth')->group(function () {
    Route::post('/register', [AuthController::class, 'register'])->name('register');
    Route::post('/login', [AuthController::class, 'login'])->name('login');
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth:sanctum');
});

Route::name('users.')->prefix('/users')->middleware('auth:sanctum')->group(function () {
    Route::get('/me', [UserController::class, 'me'])->name('me');
});

Route::name('reports.')->prefix('/reports')->middleware('auth:sanctum')->group(function () {
    Route::post('/', [ReportController::class, 'store'])->name('store');
    Route::get('/', [ReportController::class, 'index'])->name('index');
});
