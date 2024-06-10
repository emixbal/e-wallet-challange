<?php

namespace App\Http\Controllers;

use App\Jobs\UpdateWalletJob;
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
            return response()->json(['error' => $validator->messages()], 422);
        }

        $orderId = uniqid();
        $amount = $request->amount;
        $timestamp = now();

        DB::beginTransaction();
        try {
            // Save transaction
            $transaction = Transaction::create([
                'order_id' => $orderId,
                'amount' => $amount,
                'timestamp' => $timestamp,
                'user_id' => $userId,
                'status' => 0, // Pending
            ]);

            // Call third-party service
            $response = $this->paymentService->deposit($orderId, $amount, $timestamp);

            // Update transaction status
            $transaction->status = $response['status'];
            $transaction->save();

            if ($response['status'] == 1) {
                // Update wallet
                $wallet = Wallet::firstOrCreate(['user_id' => $userId]);
                $wallet->balance += $amount;
                $wallet->save();
            }
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['error' => $e->getMessage()], 500);
        }

        DB::commit();
        dispatch(new UpdateWalletJob($userId, $amount));
        return response()->json($transaction);
    }

    public function withdraw(Request $request)
    {
        $userId = $request->userLoggedIn['user_id'];
        $validator = Validator::make($request->all(), [
            'amount' => ['required', 'numeric', 'min:0'],
            'bank' => ['required', 'string', 'in:ABC,DEF,FGH'],
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->messages()], 422);
        }

        $orderId = uniqid();
        $amount = $request->input('amount');
        $bank = $request->input('bank');
        $timestamp = now();

        DB::beginTransaction();
        try {
            // Ensure the user has enough balance
            $wallet = Wallet::where('user_id', $userId)->lockForUpdate()->first();
            if ($wallet->balance < $amount) {
                throw new \Exception('Insufficient balance');
            }

            // Call third-party service
            $response = $this->paymentService->withdraw($orderId, $amount, $timestamp, $bank);

            // Save transaction
            $transaction = Transaction::create([
                'order_id' => $orderId,
                'amount' => $amount,
                'timestamp' => $timestamp,
                'user_id' => $userId,
                'status' => $response['status'],
            ]);

            if ($response['status'] == 1) {
                // Update wallet
                $wallet->balance -= $amount;
                $wallet->save();
            } else {
                throw new \Exception('Withdraw failed');
            }
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['error' => $e->getMessage()], 500);
        }

        DB::commit();
        return response()->json($transaction);
    }

    public function walletDetailByUser(Request $request)
    {
        $userId = $request->userLoggedIn['user_id'];
        // Fetch the associated wallet details from the database
        $wallet = Wallet::where('user_id', $userId)->first();

        // If the user doesn't have a wallet record, create a new one with a balance of 0
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
