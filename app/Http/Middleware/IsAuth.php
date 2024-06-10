<?php

namespace App\Http\Middleware;

use Closure;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Illuminate\Http\Request;

class IsAuth
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        $access_token = $request->header('Authorization');
        $secret_token = env('JWT_SECRET');

        if (!$access_token) {
            return response()->json([
                "status" => "nok",
                "message" => "Empty Authorization header token",
                "data" => null,
            ], 403);
        }

        try {
            $decoded = JWT::decode($access_token, new Key($secret_token, env('JWT_ALGORITHM')));
        } catch (\Firebase\JWT\ExpiredException $e) {
            return response()->json([
                "status" => "nok",
                "message" => $e->getMessage(),
                "data" => null,
            ], 403);
        } catch (\Exception $e) {
            return response()->json([
                "status" => "nok",
                "message" => $e->getMessage(),
                "data" => null,
            ], 403);
        }

        $send_to_controller = [
            'userLoggedIn' => [
                'user_id' => $decoded->user_id,
                'email' => $decoded->email,
            ],
        ];

        $request->mergeIfMissing($send_to_controller);

        return $next($request);
    }
}
