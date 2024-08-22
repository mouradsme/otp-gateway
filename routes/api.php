<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SiineOTPController;

Route::prefix('otp')->group(function () {
    Route::post('/generate', [SiineOTPController::class, 'generate']);
    Route::post('/verify', [SiineOTPController::class, 'verify']);
});
