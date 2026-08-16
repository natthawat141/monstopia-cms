<?php

use App\Http\Controllers\Admin\PageController;
use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;

Route::get('/', fn () => redirect()->route('login'));

Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.store');
});

Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth')->name('logout');

Route::prefix('admin')->middleware(['auth', 'role:admin,editor'])->name('admin.')->group(function () {
    Route::get('/dashboard', [PageController::class, 'dashboard'])->name('dashboard');
    Route::get('/{module}', [PageController::class, 'index'])->name('content.index');
    Route::get('/{module}/create', [PageController::class, 'create'])->name('content.create');
    Route::get('/{module}/{id}', [PageController::class, 'show'])->whereNumber('id')->name('content.show');
    Route::get('/{module}/{id}/edit', [PageController::class, 'edit'])->whereNumber('id')->name('content.edit');
});
