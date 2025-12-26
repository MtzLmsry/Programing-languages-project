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

        OtpService::generateAndSend($user, 'register');

        
        return response()->json([
            'status' => 'OTP sent to whatsapp. please verify to activate your account',
            'user_id' => $user->id
        ], 201);

    }

    public function verifyOtp(Request $request): JsonResponse {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'otp' => 'required|string',
        ]);

        $otp = OtpService::verify(
            User::find($request->input('user_id')),
            $request->input('otp'),
            'register'
        );

        if(!$otp){
            return response()->json([
                'status' => 'error',
                'message' => 'Invalid OTP'
            ], 400);
        } else {
            $user = User::find($request->input('user_id'));
            $otp->delete();

            return response()->json([
                'status' => 'success',
                'message' => 'OTP verified successfully. Account is now active.',
                'data' => $user
            ]);
        }
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
        $request->validate([
            'phone' => 'required|exists:users,phone',
        ]);

        $user = User::where('phone', $request->input('phone'))->first();

        OtpService::generateAndSend($user, 'forgot_password');

        return response()->json([
            'status' => 'success',
            'message' => 'OTP sent to whatsapp. please verify to reset your password',
            'user_id' => $user->id
        ]);
    }

    public function resetPassword(Request $request): JsonResponse {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'otp' => 'required|string',
            'new_password' => 'required|string|min:6|confirmed',
        ]);

        $otp = OtpService::verify(
            User::find($request->input('user_id')),
            $request->input('otp'),
            'forgot_password'
        );

        if(!$otp){
            return response()->json([
                'status' => 'error',
                'message' => 'Invalid OTP'
            ], 400);
        } else {
            $user = User::find($request->input('user_id'));
            $user->password = bcrypt($request->input('new_password'));
            $user->save();
            $otp->delete();

            return response()->json([
                'status' => 'success',
                'message' => 'Password reset successfully.',
            ]);
        }
    }
        
}