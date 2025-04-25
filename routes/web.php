<?php

use App\Http\Controllers\PotonganGajiController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

// Route::get('/', function () {
//     return view('dashboard');
// });

Route::get('/coba', function () {
    return view('coba');
})->name('coba');

Route::get('/', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    // Route::get('/', function () {
    //     return view('dashboard');
    // })->name('dashboard');
    Route::resource('/potongan-gaji', PotonganGajiController::class);
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__ . '/auth.php';