<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ensureToken
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $access_token = $request->header('Authorization');
        if (!$access_token) {
            return response()->json([
                "message" => "Empty Authorization header token",
            ], 403);
        }

        $access_token_exploded = explode(" ", $access_token);
        $head = $access_token_exploded[0];
        $token = $access_token_exploded[1];

        $token_key = env('PAYMENT_GATEWAY_API_TOKEN');

        if ($head !== "Bearer") {
            return response()->json([
                "message" => "Invalid format",
            ], 403);
        }

        if ($token !== base64_encode($token_key)) {
            return response()->json([
                "message" => "Invalid token",
            ], 403);
        }

        return $next($request);
    }
}
