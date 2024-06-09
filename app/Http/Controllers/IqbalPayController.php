<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class IqbalPayController extends Controller
{
    /**
     * Dummy deposit, acts as payment gateway
     */
    public function deposit(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'order_id' => 'required',
            'amount' => ['required', 'regex:/^\d+(\.\d{1,2})?$/'], // double type, with 2 decimals behind ex: 5000.00
            'timestamp' => ['required', 'string', 'regex:/^\d{4}-\d{2}-\d{2}\s\d{2}:\d{2}:\d{2}\.\d{6}$/'], // 2024-06-09 12:30:45.123456
        ]);

        if ($validator->fails()) {
            return response()->json([
                "message" => $validator->messages(),
            ], 422);
        }

        $formattedAmount = number_format((float)$request->amount, 2, '.', '');
        $status = rand(1, 100) <= 90 ? 1 : 2; // sometime success, sometime fail

        return response()->json((object) [
            "order_id" => $request->order_id,
            "amount" => $formattedAmount,
            "status" => $status,
        ], 200);
    }

    /**
     * Dummy withdraw, acts as payment gateway
     */
    public function withdraw(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'order_id' => 'required',
            'amount' => ['required', 'regex:/^\d+(\.\d{1,2})?$/'], // double type, with 2 decimals behind ex: 5000.00
            'bank' => 'required|string|in:ABC,DEF,FGH', // Validating allowed banks
            'destination_account' => 'required|string',
            'timestamp' => ['required', 'string', 'regex:/^\d{4}-\d{2}-\d{2}\s\d{2}:\d{2}:\d{2}\.\d{6}$/'], // 2024-06-09 12:30:45.123456
        ]);

        if ($validator->fails()) {
            return response()->json([
                "message" => $validator->messages(),
            ], 422);
        }

        $formattedAmount = number_format((float)$request->amount, 2, '.', '');
        $status = rand(1, 100) <= 90 ? 1 : 2; // sometime success, sometime fail

        return response()->json((object) [
            "order_id" => $request->order_id,
            "amount" => $formattedAmount,
            "bank" => $request->bank,
            "destination_account" => $request->destination_account,
            "status" => $status,
        ], 200);
    }
}
