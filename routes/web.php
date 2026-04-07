<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ChatController;
use App\Http\Controllers\GroupController;
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
    Route::get('/chats/{chat}', [ChatController::class, 'show'])->name('chats.show');
    Route::post('/chats/{chat}/messages', [ChatController::class, 'sendMessage'])->name('chats.message');
    Route::post('/chats/request/{chat}/respond', [ChatController::class, 'respondToRequest'])->name('chats.request.respond');

    Route::get('/groups', [GroupController::class, 'index'])->name('groups.index');
    Route::get('/groups/create', [GroupController::class, 'create'])->name('groups.create');
    Route::post('/groups', [GroupController::class, 'store'])->name('groups.store');
    Route::get('/groups/{group}', [GroupController::class, 'show'])->name('groups.show');
    Route::post('/groups/{group}/join', [GroupController::class, 'join'])->name('groups.join');
    Route::post('/groups/{group}/messages', [GroupController::class, 'sendMessage'])->name('groups.messages.store');
    Route::post('/groups/{group}/leave', [GroupController::class, 'leave'])->name('groups.leave');
    Route::post('/groups/{group}/members/{user}/kick', [GroupController::class, 'kick'])->name('groups.members.kick');
    Route::post('/groups/{group}/members/{user}/promote', [GroupController::class, 'promote'])->name('groups.members.promote');
    Route::post('/groups/{group}/members/{user}/demote', [GroupController::class, 'demote'])->name('groups.members.demote');
    Route::get('/groups/{group}/search-users', [GroupController::class, 'searchUsers'])->name('groups.search-users');
    Route::post('/groups/{group}/invite', [GroupController::class, 'invite'])->name('groups.invite');

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
