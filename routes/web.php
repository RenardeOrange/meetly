<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ChatController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\EventController;
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
    Route::get('/reset-password/{token}', [AuthController::class, 'showResetPassword'])->name('password.reset');
    Route::post('/reset-password', [AuthController::class, 'resetPassword'])->name('password.update');
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
    Route::put('/groups/{group}', [GroupController::class, 'update'])->name('groups.update');
    Route::delete('/groups/{group}', [GroupController::class, 'destroy'])->name('groups.destroy');
    Route::post('/groups/{group}/join', [GroupController::class, 'join'])->name('groups.join');
    Route::post('/groups/{group}/messages', [GroupController::class, 'sendMessage'])->name('groups.messages.store');
    Route::post('/groups/{group}/leave', [GroupController::class, 'leave'])->name('groups.leave');
    Route::post('/groups/{group}/members/{user}/kick', [GroupController::class, 'kick'])->name('groups.members.kick');
    Route::post('/groups/{group}/members/{user}/promote', [GroupController::class, 'promote'])->name('groups.members.promote');
    Route::post('/groups/{group}/members/{user}/demote', [GroupController::class, 'demote'])->name('groups.members.demote');
    Route::get('/groups/{group}/search-users', [GroupController::class, 'searchUsers'])->name('groups.search-users');
    Route::post('/groups/{group}/invite', [GroupController::class, 'invite'])->name('groups.invite');

    // Events
    Route::get('/events', [EventController::class, 'index'])->name('events.index');
    Route::get('/events/create', [EventController::class, 'create'])->name('events.create');
    Route::post('/events', [EventController::class, 'store'])->name('events.store');
    Route::get('/events/{event}', [EventController::class, 'show'])->name('events.show');
    Route::post('/events/{event}/join', [EventController::class, 'join'])->name('events.join');
    Route::post('/events/{event}/cancel-join', [EventController::class, 'cancelJoin'])->name('events.cancel-join');
    Route::post('/events/{event}/cancel', [EventController::class, 'cancel'])->name('events.cancel');
    Route::post('/events/{event}/respond/{userId}', [EventController::class, 'respondRequest'])->name('events.respond');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');

    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::delete('/dashboard/swipe/{match}', [DashboardController::class, 'undo'])->name('dashboard.undo');

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

        Route::get('/groups', [AdminController::class, 'groups'])->name('groups');
        Route::put('/groups/{group}', [AdminController::class, 'updateGroup'])->name('groups.update');
        Route::delete('/groups/{group}', [AdminController::class, 'deleteGroup'])->name('groups.delete');

        Route::get('/events', [AdminController::class, 'events'])->name('events');
        Route::put('/events/{event}', [AdminController::class, 'updateEvent'])->name('events.update');
        Route::delete('/events/{event}', [AdminController::class, 'deleteEvent'])->name('events.delete');
    });
});
