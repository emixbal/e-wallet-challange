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

Route::middleware(['auth'])->group(function () {
    Route::post('/deposit', [WalletController::class, 'deposit']);
    Route::post('/withdraw', [WalletController::class, 'withdraw']);
});

// for dummy third party payment gateway
Route::group(['prefix' => 'iqbalpay', 'middleware' => ['name_token']], function () {
    Route::post('/deposit', [App\Http\Controllers\IqbalPayController::class, 'deposit'])->name('iqbalpay.deposit');
});

// for dummy third party payment gateway
Route::group(['prefix' => 'iqbalpay'], function () {
    Route::get('/get_token', function (Request $request) {
        return base64_encode(env('PAYMENT_GATEWAY_API_TOKEN'));
    })->name('iqbalpay.get_token');
});
