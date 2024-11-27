<?php

use App\Http\Controllers\AbsenController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\QrCodeController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::post('/validate-qr-code', [QrCodeController::class, 'validateQrCode']);

Route::post('login', [AuthController::class, 'login']);

Route::get('data-absen', [AbsenController::class, 'index']);