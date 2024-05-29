<?php

use App\Enums\RoleEnum;
use App\Http\Controllers\ProfileController;
use App\Livewire\{Assistants, Developers};
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return auth()->check() ? to_route('developers.index') : to_route('login');
})->name('home');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::middleware('role:' . RoleEnum::CTO->value)
        ->group(function () {
            Route::get('/assistants', Assistants\Index::class)->name('assistants.index');
        });

    Route::get('/developers', Developers\Index::class)->name('developers.index');
});

require __DIR__ . '/auth.php';
