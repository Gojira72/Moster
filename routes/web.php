<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\RegisterController;

Route::get('/', function () {
    return view('usuario.welcome'); // resources/views/usuario/welcome.blade.php
})->name('welcome');

// Página restrita "teste" (apenas para usuários logados)
Route::middleware('auth')->group(function() {
    Route::get('/teste', function() {
        return view('usuario.teste'); // resources/views/usuario/teste.blade.php
    })->name('teste');
});

// Login
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login.index');
Route::post('/login', [AuthController::class, 'login'])->name('login.store');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Registro
Route::get('/register', [RegisterController::class, 'index'])->name('register.index');
Route::post('/register', [RegisterController::class, 'store'])->name('register.store');
