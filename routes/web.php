<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\ContestantController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\AuthController;

Route::get('/', function () {
    return view('login');
});

Route::get('/admin/dashboard', function () {
    return view('admin.dashboard');
})->name('admin.dashboard');

// ─── Admin Users ─────────────────────────────────────────────────
Route::get('/admin/user-roles',          [UserController::class, 'index'])->name('admin.user-roles');
Route::post('/admin/users',              [UserController::class, 'store'])->name('admin.users.store');
Route::put('/admin/users/{user}',        [UserController::class, 'update'])->name('admin.users.update');
Route::delete('/admin/users/{user}',     [UserController::class, 'destroy'])->name('admin.users.destroy');

// Archive
Route::get('/admin/users/archive',           [UserController::class, 'archive'])->name('admin.user-archive');
Route::post('/admin/users/{id}/restore',     [UserController::class, 'restore'])->name('admin.users.restore');
Route::delete('/admin/users/{id}/force',     [UserController::class, 'forceDelete'])->name('admin.users.force-delete');

// ─── Admin Contestants ───────────────────────────────────────────
Route::get('/admin/contestants', [ContestantController::class, 'index'])->name('admin.contestants');

// ─── Admin Events ────────────────────────────────────────────────
Route::get('/admin/events',              [EventController::class, 'index'])->name('admin.events');
Route::get('/admin/events/create',       [EventController::class, 'create'])->name('admin.events.create');
Route::post('/admin/events',             [EventController::class, 'store'])->name('admin.events.store');
Route::get('/admin/events/{event}/edit', [EventController::class, 'edit'])->name('admin.events.edit');
Route::put('/admin/events/{event}',      [EventController::class, 'update'])->name('admin.events.update');
Route::delete('/admin/events/{event}',   [EventController::class, 'destroy'])->name('admin.events.destroy');

// ─── Judge ───────────────────────────────────────────────────────
Route::get('/judge/dashboard', function () {
    return view('judge.dashboard');
})->name('judge.dashboard');

// ─── SAS ─────────────────────────────────────────────────────────
Route::get('/sas/dashboard', function () {
    return view('sas.dashboard');
})->name('sas.dashboard');
Route::get('/sas/contestants', [ContestantController::class, 'index'])->name('sas.contestants');

// ─── Auth ────────────────────────────────────────────────────────
Route::get('/auth/microsoft',          [AuthController::class, 'redirect'])->name('auth.microsoft');
Route::get('/auth/microsoft/callback', [AuthController::class, 'callback'])->name('auth.callback');
Route::post('/logout',                 [AuthController::class, 'logout'])->name('auth.logout');

// ─── TEMPORARY DEV LOGIN (remove before production) ──────────────
Route::get('/dev-login/{role}', function ($role) {
    $user = App\Models\User::where('role', $role)->where('status', 'active')->first();
    if ($user) {
        Auth::login($user);
        return match($role) {
            'admin' => redirect()->route('admin.dashboard'),
            'judge' => redirect()->route('judge.dashboard'),
            'sas'   => redirect()->route('sas.dashboard'),
            default => redirect('/'),
        };
    }
    return 'No user found with role: ' . $role;
})->middleware('web');
