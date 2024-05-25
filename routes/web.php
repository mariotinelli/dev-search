<?php

use App\Http\Controllers\ProfileController;
use App\Http\Middleware\CtoMiddleware;
use App\Livewire\Assistants;
use Illuminate\Support\Facades\Route;


//Route::any('/', function () {
//    // ...
//})->name('home');

Route::get('/')->name('home');

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::middleware(CtoMiddleware::class)
        ->group(function () {
            Route::get('/assistants', Assistants\Index::class)->name('assistants.index');
        });
});

Route::permanentRedirect('/', redirectByLoggedUser());

require __DIR__ . '/auth.php';
