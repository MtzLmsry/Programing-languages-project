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
      public function register(RegisterRequest $request): JsonResponse
    {
        $user = User::firstOrCreate(
            ['phone' => $request->phone],
            [
                'FirstName' => $request->FirstName,
                'LastName' => $request->LastName,
                'password' => bcrypt($request->password),
                'Birthdate' => $request->BirthDate,
                'account_status' => 'Inactive',
                'personalPhoto' => $request->file('personalPhoto')->store('user/personal', 'public'),
                'idPhotoFront' => $request->file('idPhotoFront')->store('users/id', 'public'),
                'idPhotoBack' => $request->file('idPhotoBack')->store('users/id', 'public'),
            ]
        );

        OtpService::send($user, 'register');

        return response()->json([
            'status' => 'success',
            'message' => 'OTP sent to WhatsApp. Please verify to activate your account',
            'user_id' => $user->id
        ], 201);
    }

    public function verifyOtp(Request $request): JsonResponse
    {
        $request->validate([
            'phone' => 'required',
            'code' => 'required'
        ]);

        $user = User::where('phone', $request->phone)->first();
        if (!$user) {
            return response()->json(['message' => 'User not found'], 404);
        }

        if (!OtpService::verify($user, $request->code, 'register')) {
            return response()->json(['message' => 'Invalid OTP'], 422);
        }


        return response()->json(['message' => 'Account verified successfully']);
    }

    public function login(LoginRequest $request): JsonResponse
    {
        if (!Auth::attempt($request->only('phone', 'password'))) {
            return response()->json([
                'status' => 'error',
                'message' => 'Invalid credentials'
            ], 401);
        }

        $user = User::where('phone', $request->phone)->first();

        if ($user->account_status !== 'Active') {
            return response()->json([
                'status' => 'Success',
                'message' => 'Note: Account is not active'
            ]);
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

    public function logout(Request $request): JsonResponse
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'User logged out successfully'
        ]);
    }



    public function forgotPassword(Request $request): JsonResponse 
    {
        $request->validate(['phone' => 'required|exists:users,phone']);

        $user = User::where('phone', $request->phone)->first();
        OtpService::send($user, 'forgot_password');

        return response()->json(['message' => 'OTP sent']);
    }

    public function resetPassword(Request $request): JsonResponse 
    {
        $request->validate([
            'phone' => 'required',
            'code' => 'required',
            'password' => 'required|min:6'
        ]);

        $user = User::where('phone', $request->phone)->first();
        if (!$user) {
            return response()->json(['message' => 'User not found'], 404);
        }

        if (!OtpService::verify($user, $request->code, 'forgot_password')) {
            return response()->json(['message' => 'Invalid OTP'], 422);
        }

        $user->update(['password' => bcrypt($request->password)]);

        return response()->json(['message' => 'Password updated']);
    }
}