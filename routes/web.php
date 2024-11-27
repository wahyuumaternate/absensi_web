<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\QrCodeController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Artisan;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/migrate-fresh', function () {
    // Panggil artisan command untuk migrate fresh
    Artisan::call('migrate:fresh --seed');

    return response()->json(['message' => 'Database migrated fresh successfully.'], 200);
})->name('migrate.fresh');

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';

Route::get('/qrcode', [QrCodeController::class, 'show']);

Route::get('/qr-code', [QrCodeController::class, 'getQrCode']);