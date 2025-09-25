<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\CartaoController;
use App\Http\Controllers\Api\ContaController;
use App\Http\Controllers\Api\TransacaoController;
use App\Http\Controllers\Api\TransferenciaController;
use App\Http\Controllers\Api\UsuarioController;
use Illuminate\Support\Facades\Route;

Route::prefix('auth')->group(function () {
    Route::post('login', [AuthController::class, 'login']);

    Route::middleware('auth:sanctum')->group(function () {
        Route::post('logout', [AuthController::class, 'logout']);
    });
});

Route::middleware('auth:sanctum')->group(function () {
    Route::get('users/me', [UsuarioController::class, 'me']);
    Route::get('accounts/me', [ContaController::class, 'me']);
    Route::get('transactions', [TransacaoController::class, 'index']);
    Route::get('cards/me', [CartaoController::class, 'me']);
    Route::post('transfers', [TransferenciaController::class, 'store']);
});
