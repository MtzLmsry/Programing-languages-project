<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckAccountStatus
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user(); 

        if (!$user) {
            return response()->json([
                'message' => 'Unauthenticated'
            ], 401);
        }

        if ($user->account_status !== 'active') {
            return response()->json([
                'message' => 'Your account is not active. Please wait for admin approval.'
            ], 403);
        }

        return $next($request);
    }
}