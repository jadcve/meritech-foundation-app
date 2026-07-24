<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::get('/tenant/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified', 'tenant.resolve'])->name('tenant.dashboard');

Route::get('/tenant/settings', function () {
    return response('Tenant settings');
})->middleware(['auth', 'verified', 'tenant.resolve', 'tenant.authorization', 'permission:settings.view'])->name('tenant.settings');

Route::patch('/tenant/settings', function () {
    return response('Tenant settings updated');
})->middleware(['auth', 'verified', 'tenant.resolve', 'tenant.authorization', 'permission:settings.update'])->name('tenant.settings.update');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
