<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Ui\PreviewController;
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

Route::prefix('ui/{vertical}')->group(function () {
    Route::get('/', [PreviewController::class, 'dashboard'])->name('ui.preview.dashboard');
    Route::get('/agenda', [PreviewController::class, 'agenda'])->name('ui.preview.agenda');
    Route::get('/personas', [PreviewController::class, 'people'])->name('ui.preview.people');
    Route::get('/personas/{person}', [PreviewController::class, 'person'])->name('ui.preview.person');
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
