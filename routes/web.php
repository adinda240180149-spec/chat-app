<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;

Route::get('/', function () {
    return view('welcome');
});

Route::middleware('guest')->group(function () {
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
});

Route::middleware('auth')->group(function () {
    Route::get('/dashboard', function () {
        return 'Dashboard (Akan diganti di Stage 6). User login: ' . auth()->user()->name . ' (@' . auth()->user()->username . ')<br><br>' .
               '<form action="' . route('logout') . '" method="POST">' . csrf_field() . '<button type="submit">Logout</button></form>';
    })->name('dashboard');
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
});
