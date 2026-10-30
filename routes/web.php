<?php

use App\Http\Controllers\Admin;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\PortfolioController;
use Illuminate\Support\Facades\Route;

Route::get('/', [PortfolioController::class, 'index'])->name('home');
Route::post('/contact', [PortfolioController::class, 'contact'])->middleware('throttle:5,1')->name('contact.store');
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'create'])->name('login');
    Route::post('/login', [AuthController::class, 'store'])->middleware('throttle:20,1')->name('login.store');
});
Route::post('/logout', [AuthController::class, 'destroy'])->middleware('auth')->name('logout');
Route::prefix('admin')->name('admin.')->middleware('auth')->group(function () {
    Route::get('/', Admin\DashboardController::class)->name('dashboard');
    Route::get('/profile', [Admin\ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile', [Admin\ProfileController::class, 'update'])->name('profile.update');
    Route::resource('projects', Admin\ProjectController::class)->except('show');
    Route::resource('skills', Admin\SkillController::class)->only(['index', 'store', 'update', 'destroy']);
    Route::resource('messages', Admin\MessageController::class)->only(['index', 'show', 'destroy']);
});
