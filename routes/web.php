<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ContestantController;
use App\Http\Controllers\UserController;

Route::get('/', function ()
{
    return view('login');
});

Route::get('/admin/dashboard', function ()
{
    return view('admin.dashboard');
})->name('admin.dashboard');

/* USER ROLES PAGE */
Route::get('/admin/user-roles', [UserController::class, 'index'])->name('admin.user-roles');

// ─── Judge ───────────────────────────────
Route::get('/judge/dashboard', function ()
{
    return view('judge.dashboard');
})->name('judge.dashboard');

// ─── SAS ─────────────────────────────────
Route::get('/sas/dashboard', function ()
{
    return view('sas.dashboard');
})->name('sas.dashboard');

Route::get('/admin/contestants', [ContestantController::class, 'index'])->name('admin.contestants');
Route::get('/sas/contestants', [ContestantController::class, 'index'])->name('sas.contestants');

Route::get('/users', [UserController::class, 'index'])->name('users.index');
Route::post('/users', [UserController::class, 'store'])->name('users.store');
Route::put('/users/{user}', [UserController::class, 'update'])->name('users.update');
