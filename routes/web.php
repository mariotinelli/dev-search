<?php

use App\Http\Controllers\ProfileController;
use App\Http\Middleware\CtoMiddleware;
use App\Livewire\{Assistants, Developers};
use Illuminate\Support\Facades\Route;

Route::get('/', fn () => to_route(redirectByLoggedUser()))->name('home');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::middleware(CtoMiddleware::class)
        ->group(function () {
            Route::get('/assistants', Assistants\Index::class)->name('assistants.index');
            Route::get('/developers', Developers\Index::class)->name('developers.index');
        });
});

require __DIR__ . '/auth.php';
