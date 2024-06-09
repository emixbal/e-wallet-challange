<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class IqbalPayController extends Controller
{
    /**
     * dummy deposit, act as payment gateway
     */
    public function deposit(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'order_id' => 'required',
            'amount' => ['required', 'regex:/^\d+(\.\d{1,2})?$/'], // double type. with 2 decimal behind ex: 5000.00
            'timestamp' => ['required', 'string', 'regex:/^\d{4}-\d{2}-\d{2}\s\d{2}:\d{2}:\d{2}\.\d{6}$/'], //2024-06-09 12:30:45.123456
        ]);

        if ($validator->fails()) {
            return response()->json([
                "message" => $validator->messages(),
            ], 422);
        }

        $formattedAmount = number_format((float)$request->amount, 2, '.', '');
        $status = rand(1, 100) <= 90 ? 1 : 2;

        return response()->json((object) [
            "order_id" => $request->order_id,
            "amount" => number_format((float)$request->amount, 2, '.', ''),
            "status" => $status,
        ], 200);
    }
}
