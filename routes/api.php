<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\WalletController;
use App\Http\Controllers\TransactionController;

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

Route::post('/login', [App\Http\Controllers\AuthController::class, 'login_process'])->name('api.login');

Route::group(['middleware' => ['if_auth']], function () {
    Route::post('/deposit', [WalletController::class, 'deposit'])->name('wallet.deposit');
    Route::post('/withdraw', [WalletController::class, 'withdraw'])->name('wallet.withdraw');
    Route::post('/payment', [WalletController::class, 'payment'])->name('wallet.payment');
    Route::get('/wallet-detail', [WalletController::class, 'walletDetailByUser'])->name('wallet.wallet_detail');
    Route::get('/transactions', [WalletController::class, 'listTransactions'])->name('wallet.transaction_list');
});

Route::group(['prefix'=>'admin-transactions','middleware' => ['if_auth', 'if_admin']], function () {
    Route::get('/', [TransactionController::class, 'index'])->name('transactions.list');
});

// for dummy third party payment gateway
Route::group(['prefix' => 'iqbalpay', 'middleware' => ['name_token']], function () {
    Route::get('/deposit', [App\Http\Controllers\IqbalPayController::class, 'debug'])->name('iqbalpay.deposit');
    Route::post('/deposit', [App\Http\Controllers\IqbalPayController::class, 'deposit'])->name('iqbalpay.deposit');
    Route::post('/withdraw', [App\Http\Controllers\IqbalPayController::class, 'withdraw'])->name('iqbalpay.withdraw');
});

// for dummy third party payment gateway
Route::group(['prefix' => 'iqbalpay'], function () {
    Route::get('/get_token', function (Request $request) {
        return base64_encode(env('PAYMENT_GATEWAY_API_TOKEN'));
    })->name('iqbalpay.get_token');
});
