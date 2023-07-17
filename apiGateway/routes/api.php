<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::controller(\App\Http\Controllers\RefectoryController::class)->group(function () {
    Route::post('/refectory/login-qr', 'loginQr')->name("loginQr");
});

Route::controller(\App\Http\Controllers\PaymentController::class)->group(function () {
    Route::post('/payment/pay', 'pay')->name("pay");
});
