<?php

namespace App\Http\Controllers;

use App\Jobs\PaymentJob;
use App\Jobs\TopupWalletJob;
use App\Jobs\WithdrawJob;
use App\Models\Transaction;
use App\Models\Wallet;
use App\Services\PaymentService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class WalletController extends Controller
{
    private $paymentService;

    public function __construct(PaymentService $paymentService, Request $request)
    {
        $this->paymentService = $paymentService;
    }

    public function deposit(Request $request)
    {
        $userId = $request->userLoggedIn['user_id'];

        $validator = Validator::make($request->all(), [
            'amount' => ['required', 'numeric', 'min:1000'],
        ]);

        if ($validator->fails()) {
            return response()->json([
                'error' => $validator->messages(),
                "message" => "something went wrong",
                "status" => "nok",
            ], 422);
        }

        $orderId = uniqid();
        $amount = $request->amount;
        $timestamp = now()->format('Y-m-d H:i:s.u');

        DB::beginTransaction();
        try {
            // Call third-party service
            $response = $this->paymentService->deposit($orderId, $amount, $timestamp);

            // Save transaction
            $transaction = Transaction::create([
                'order_id' => $orderId,
                'amount' => $amount,
                'timestamp' => $timestamp,
                'user_id' => $userId,
                'is_success' => false,
                'status' => 1, // Mark as deposit transaction
            ]);

            if ($response['status'] == 1) {
                $transaction->is_success = true;
                $transaction->save();

                dispatch(new TopupWalletJob($userId, $amount));
            }

            // Update wallet
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json((object) [
                'error' => $e->getMessage(),
                "message" => "something went wrong",
                "status" => "nok",
                "response" => $response,
            ], 200);
        }

        DB::commit();
        return response()->json((object) [
            "error" => null,
            "message" => "success",
            "status" => "ok",
        ]);
    }

    public function withdraw(Request $request)
    {
        $userId = $request->userLoggedIn['user_id'];
        $validator = Validator::make($request->all(), [
            'amount' => ['required', 'numeric', 'min:10000'],
            'account' => ['required', 'numeric', 'min:0'],
            'bank' => ['required', 'string', 'in:ABC,DEF,FGH'],
        ]);

        if ($validator->fails()) {
            return response()->json([
                'error' => $validator->messages(),
                "message" => "Something went wrong",
                "status" => "nok",
            ], 422);
        }

        $orderId = uniqid();
        $amount = $request->amount;
        $bank = $request->bank;
        $account = $request->account;
        $timestamp = now()->format('Y-m-d H:i:s.u');

        DB::beginTransaction();
        try {
            // save transaction
            $transaction = Transaction::create([
                'order_id' => $orderId,
                'amount' => $amount,
                'timestamp' => $timestamp,
                'user_id' => $userId,
                'is_success' => false,
                'status' => 2, // Mark as withdrawal transaction
            ]);

            // Call third-party service
            $response = $this->paymentService->withdraw($orderId, $amount, $timestamp, $bank, $account);

            if ($response['status'] == 1) {
                $transaction->is_success = true;
                $transaction->save();

                // Dispatch WithdrawJob
                dispatch(new WithdrawJob($userId, $amount));
            }
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json((object) [
                'error' => $e->getMessage(),
                "message" => "Something went wrong",
                "status" => "nok",
            ], 200);
        }

        DB::commit();
        return response()->json((object) [
            "error" => null,
            "status" => "ok",
            "message" => "success",
        ]);
    }

    public function payment(Request $request)
    {
        $userId = $request->userLoggedIn['user_id'];
        $validator = Validator::make($request->all(), [
            'amount' => ['required', 'numeric', 'min:1000'],
        ]);

        if ($validator->fails()) {
            return response()->json([
                'error' => $validator->messages(),
                "message" => "Something went wrong",
                "status" => "nok",
            ], 422);
        }

        $amount = $request->amount;
        $timestamp = now()->format('Y-m-d H:i:s.u');

        DB::beginTransaction();
        try {
            // dispatch PaymentJob
            dispatch(new PaymentJob($userId, $amount));

            // Save transaction
            $transaction = Transaction::create([
                'order_id' => uniqid(),
                'amount' => $amount,
                'timestamp' => $timestamp,
                'user_id' => $userId,
                'status' => 3, // Mark as payment transaction
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json((object) [
                'error' => $e->getMessage(),
                "message" => "Something went wrong",
                "status" => "nok",
            ], 200);
        }

        DB::commit();
        return response()->json((object) [
            "error" => null,
            "status" => "ok",
            "message" => "success",
        ]);
    }

    public function walletDetailByUser(Request $request)
    {
        $userId = $request->userLoggedIn['user_id'];
        // Fetch the associated wallet details from the database
        $wallet = Wallet::where('user_id', $userId)->first();

        // If user doesn't have a wallet record, create a new one
        if (!$wallet) {
            $wallet = new Wallet();
            $wallet->user_id = $userId;
            $wallet->balance = (float) 0.00;
        }

        return response()->json([
            "status" => "ok",
            "message" => "success",
            "data" => (object) [
                'user_id' => $userId,
                'wallet' => $wallet,
            ],
        ], 200);
    }

    public function listTransactions(Request $request)
    {
        $userId = $request->userLoggedIn['user_id'];
        $transactions = Transaction::where('user_id', $userId)->orderBy('created_at', 'desc')->get();
        return response()->json([
            "status" => "ok",
            "message" => "success",
            "data" => $transactions,
        ], 200);
    }

}
