<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ChatController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\InteretController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SwipeController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('login');
});

Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);

    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);

    Route::get('/forgot-password', [AuthController::class, 'showForgotPassword'])->name('password.request');
    Route::post('/forgot-password', [AuthController::class, 'sendResetLink'])->name('password.email');
});

Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    Route::get('/home', [HomeController::class, 'index'])->name('home');
    Route::post('/swipe', [SwipeController::class, 'swipe'])->name('swipe');

    Route::get('/chats', [ChatController::class, 'index'])->name('chats');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');

    Route::get('/interets', [InteretController::class, 'index'])->name('interets.index');
    Route::post('/interets/toggle', [InteretController::class, 'toggle'])->name('interets.toggle');

    Route::middleware('admin')->prefix('admin')->name('admin.')->group(function () {
        Route::get('/', [AdminController::class, 'dashboard'])->name('dashboard');
        Route::get('/users', [AdminController::class, 'users'])->name('users');
        Route::put('/users/{user}', [AdminController::class, 'updateUser'])->name('users.update');
        Route::post('/users/{user}/blacklist', [AdminController::class, 'toggleBlacklist'])->name('users.blacklist');
        Route::delete('/users/{user}', [AdminController::class, 'deleteUser'])->name('users.delete');

        Route::get('/interets', [AdminController::class, 'interets'])->name('interets');
        Route::post('/interets', [AdminController::class, 'storeInteret'])->name('interets.store');
        Route::put('/interets/{interet}', [AdminController::class, 'updateInteret'])->name('interets.update');
        Route::delete('/interets/{interet}', [AdminController::class, 'deleteInteret'])->name('interets.delete');
    });
});
