<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class IsAdmin
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
        if (!array_key_exists('role', $request->userLoggedIn)) {
            return response()->json([
                "status" => "nok",
                "message" => "Unauthorized 1",
                "data" => null,
            ], 403);
        }

        if ($request->userLoggedIn['role'] != "admin") {
            return response()->json([
                "status" => "nok",
                "message" => "Unauthorized",
                "data" => null,
            ], 403);
        }

        return $next($request);
    }
}
