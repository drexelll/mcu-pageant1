<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\sas\ContestantController;
use App\Http\Controllers\admin\UserController;

use App\Http\Controllers\admin\EventController as AdminEventController;
use App\Http\Controllers\sas\EventController as SasEventController;

use App\Http\Controllers\AuthController;

Route::get('/', function () {
    return view('login');
});

Route::get('/admin/dashboard', function () { return view('admin.dashboard'); })->name('admin.dashboard');

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
Route::get('/admin/events',              [AdminEventController::class, 'index'])->name('admin.events');

Route::get('/admin/events/create', [AdminEventController::class, 'create'])->name('admin.events.create');

Route::post('/admin/events',             [AdminEventController::class, 'store'])->name('admin.events.store');
Route::get('/admin/events/{event}/edit', [AdminEventController::class, 'edit'])->name('admin.events.edit');
Route::put('/admin/events/{event}',      [AdminEventController::class, 'update'])->name('admin.events.update');
Route::delete('/admin/events/{event}',   [AdminEventController::class, 'destroy'])->name('admin.events.destroy');
Route::put('/admin/events/{event}/assign/{type}', [AdminEventController::class, 'assign'])->name('admin.events.assign');
Route::delete('/admin/events/{event}/unassign/{type}/{id}', [AdminEventController::class, 'unassign'])->name('admin.events.unassign');

// ─── Judge ───────────────────────────────────────────────────────
Route::get('/judge/dashboard', function () { return view('judge.dashboard'); })->name('judge.dashboard');

// ─── SAS ─────────────────────────────────────────────────────────
Route::get('/sas/dashboard', function () { return view('sas.dashboard'); })->name('sas.dashboard');

Route::get('/sas/events',              [SasEventController::class, 'index'])->name('sas.events');
Route::get('/sas/contestants', [ContestantController::class, 'index'])->name('sas.contestants');
Route::get('/sas/contestants/create', [ContestantController::class, 'create'])->name('sas.contestants.create');
Route::get('/sas/contestants/archive',                   [ContestantController::class, 'archive'])->name('sas.contestants.archive');
Route::delete('/sas/contestants/{contestant}',          [ContestantController::class, 'destroy'])->name('sas.contestants.destroy');
Route::post('/sas/contestants/{id}/restore',             [ContestantController::class, 'restore'])->name('sas.contestants.restore');
Route::delete('/sas/contestants/{id}/force',             [ContestantController::class, 'forceDelete'])->name('sas.contestants.force-delete');
Route::post('/sas/contestants', [ContestantController::class, 'store'])->name('sas.contestants.store');
Route::get('/sas/contestants/{contestant}/edit', [ContestantController::class, 'edit'])->name('sas.contestants.edit');
Route::put('/sas/contestants/{contestant}', [ContestantController::class, 'update'])->name('sas.contestants.update');

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

Route::get('/auth/google',          [AuthController::class, 'redirectGoogle'])->name('auth.google');
Route::get('/auth/google/callback', [AuthController::class, 'callbackGoogle'])->name('auth.google.callback');
Route::post('/auth/login', [AuthController::class, 'login'])->name('auth.login');
