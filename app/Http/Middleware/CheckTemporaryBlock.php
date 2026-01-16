<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
class CheckTemporaryBlock
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = Auth::user();

        if( $user && $user->bloocked_until && $user->account_status == 'Inactive') {

            if (now()->lessThan($user->bloocked_until)) {
                $user->update(['account_status' => 'Active', 'bloocked_until' => null]);
                return $next($request);
            }

            return response()->json([
                'status' => 'error',
                'message' => 'Your account is temporarily blocked. Please contact support.'
            ], 403);
        }
        return $next($request);
    }
}
