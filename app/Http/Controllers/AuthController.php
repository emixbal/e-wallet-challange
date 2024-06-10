<?php

namespace App\Http\Controllers;

use App\Models\User;
use Firebase\JWT\JWT;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Ramsey\Uuid\Uuid;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $pass = [
            "page" => [
                "parent_title" => "Login",
                "title" => "Login",
            ],
            "data" => [],
        ];

        return view("login/login_form", $pass);
    }

    public function login_process(Request $request)
    {
        if (!$request->email || !$request->password) {
            return response()->json([
                "status" => "nok",
                "message" => "empty email or password",
                "data" => null,
            ], 422);
        }

        $user = User::where('email', $request->email)->first();

        if (!$user) {
            return response()->json([
                "status" => "nok",
                "message" => "unregistered email",
                "data" => null,
            ], 422);
        }

        if (!Hash::check($request->password, $user->password)) {
            return response()->json([
                "status" => "nok",
                "message" => "wrong password",
                "data" => null,
            ], 422);
        }

        $now = time();
        $time_to_refresh = $now + (5 * 60);
        $uuid = Uuid::uuid4()->toString();
        $secret_token = env('JWT_SECRET');

        $payload = [
            'email' => $user->email,
            'user_id' => $user->id,
            'iat' => $now,
        ];

        $payload = $payload;

        $access_token = JWT::encode($payload, $secret_token, env('JWT_ALGORITHM'));

        return response()->json([
            "status" => "ok",
            "message" => "success",
            "data" => (object) [
                "uuid" => $uuid,
                "access_token" => $access_token,
            ],
        ], 200);
    }
}
