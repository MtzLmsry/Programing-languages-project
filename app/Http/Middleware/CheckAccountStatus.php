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

        if ($user->bloocked_until && now()->lessThan($user->bloocked_until)) {
            return response()->json([
                'message' => 'Your account is temporarily blocked until ' . $user->bloocked_until->toDateTimeString()
            ], 403);
        }

        if ($user->bloocked_until && now()->greaterThanOrEqualTo($user->bloocked_until)) {
            $user->update([
                'account_status' => 'Active',
                'bloocked_until' => null
            ]);
        }

        if ($user->account_status !== 'Active') {
            return response()->json([
                'message' => 'Your account is not active. Please wait for admin approval.'
            ], 403);
        }

        return $next($request);
    }
}