<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;

Route::get('/', function () {
    return view('welcome');
});

use App\Http\Controllers\ChatController;

Route::middleware('guest')->group(function () {
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
});

Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [ChatController::class, 'index'])->name('dashboard');
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    
    // Rute Chatting
    Route::post('/chats/private', [ChatController::class, 'startPrivateChat'])->name('chats.private');
    Route::get('/chats/{chat}', [ChatController::class, 'viewChat'])->name('chats.view');
    Route::post('/chats/{chat}/messages', [ChatController::class, 'sendMessage'])->name('messages.send');
});
