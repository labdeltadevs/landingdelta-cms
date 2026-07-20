<?php

use App\Http\Controllers\Api\AuthController;
use Illuminate\Support\Facades\Route;

Route::post('/login', [AuthController::class, 'login'])
    ->middleware('throttle:login')
    ->name('api.login');

Route::middleware(['auth:sanctum'])->group(function (): void {
    Route::get('/user', [AuthController::class, 'user'])
        ->name('api.user');

    Route::post('/generate-token', [AuthController::class, 'generateToken'])
        ->middleware('role:admin|editor')
        ->name('api.token.generate');

    Route::post('/revoke-token', [AuthController::class, 'revokeToken'])
        ->middleware('role:admin|editor')
        ->name('api.token.revoke');

    Route::post('/logout', [AuthController::class, 'logout'])
        ->name('api.logout');
});
