<?php

use App\Http\Controllers\ProfileController;
use App\Livewire\Assistants;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::prefix('/assistants')
        ->name('assistants.')
        ->group(function () {
            Route::get('/', Assistants\Index::class)->name('index');
            Route::get('/create', Assistants\Create::class)->name('create');
            Route::get('/{id}/edit', Assistants\Index::class)->name('edit');
        });
});

require __DIR__ . '/auth.php';
