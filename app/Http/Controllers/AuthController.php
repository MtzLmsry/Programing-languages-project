<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use App\Services\OtpService;

class AuthController extends Controller
{
    public function register(RegisterRequest $request): JsonResponse {
        
        $user = User::create([
            'FirstName' => $request['FirstName'],
            'LastName' => $request['LastName'],
            'phone' => $request['phone'],
            'password' => bcrypt($request['password']),
            'Birthdate' => $request['BirthDate'],
            'account_status' => 'Inactive',
            'personalPhoto' => $request->file('personalPhoto')->store('user/personal', 'public'),
            'idPhotoFront' => $request->file('idPhotoFront')->store('users/id', 'public'),
            'idPhotoBack' => $request->file('idPhotoBack')->store('users/id', 'public'),
        ]);

        OtpService::send($user->phone, 'register');

        
        return response()->json([
            'status' => 'OTP sent to whatsapp. please verify to activate your account',
            'user_id' => $user->id
        ], 201);

    }

    public function verifyOtp(Request $request): JsonResponse {
       $request->validate([
        'phone' => 'required',
        'code' => 'required'
    ]);

    if (!OtpService::verify($request->phone, $request->code, 'register')) {
        return response()->json(['message' => 'Invalid OTP'], 422);
    }

    User::where('phone', $request->phone)
        ->update(['account_status' => 'Active']);

    return response()->json(['message' => 'Account activated']);
    }

    public function login(LoginRequest $request): JsonResponse {
        
        
         if (!Auth::attempt($request->only('phone', 'password'))) {
            return response()->json([
                'status' => 'error',
                'message' => 'Invalid credentials'
            ], 500);
        }
        $user = User::query()->where('phone', $request['phone'])->first();
        if($user->account_status !== 'Active'){
            return response()->json([
                'status' => 'error',
                'message' => 'Account is not active'
            ], 403);
        }
        $token = $user->createToken('API_TOKEN')->plainTextToken;
        
        return response()->json([
            'status' => 'success',
            'data' => [
                'token' => $token,
                'user' => $user
            ],
            'message' => 'User logged in successfully'
        ]);
        
    }
    public function logout(Request $request): JsonResponse {
        $request->user()->currentAccessToken()->delete();
        
        return response()->json([
            'status' => 'success',
            'message' => 'User logged out successfully'
        ]);
    }

    public function forgotPassword(Request $request): JsonResponse {
            $request->validate(['phone' => 'required|exists:users,phone']);

    OtpService::send($request->phone, 'forgot_password');

    return response()->json(['message' => 'OTP sent']);
    }

    public function resetPassword(Request $request): JsonResponse {
        $request->validate([
        'phone' => 'required',
        'code' => 'required',
        'password' => 'required|min:6'
    ]);

    if (!OtpService::verify($request->phone, $request->code, 'forgot_password')) {
        return response()->json(['message' => 'Invalid OTP'], 422);
    }

    User::where('phone', $request->phone)
        ->update(['password' => bcrypt($request->password)]);

    return response()->json(['message' => 'Password updated']);
    }
        
}