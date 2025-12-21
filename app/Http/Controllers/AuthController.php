<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use App\Models\User;
use Illuminate\Support\Facades\Auth;


class AuthController extends Controller
{
    public function register(RegisterRequest $request): JsonResponse {
        
        $user = User::create([
            'first_name' => $request['FirstName'],
            'last_name' => $request['LastName'],
            'phone' => $request['phone'],
            'password' => bcrypt($request['password']),
            'birth_date' => $request['BirthDate'],
            'personal_photo' => $request->file('personalPhoto')->store('user/personal', 'public'),
            'id_photo_front' => $request->file('idPhotoFront')->store('users/id', 'public'),
            'id_photo_back' => $request->file('idPhotoBack')->store('users/id', 'public'),
        ]);

        $token = $user->createToken('API_TOKEN')->plainTextToken;
        
        return response()->json([
            'status' => 'success',
            'data' => [
                'token' => $token,
                'user' => $user
            ],
            'message' => 'User registered successfully'
        ]);

    }

    public function login(LoginRequest $request): JsonResponse {
        
        
         if (!Auth::attempt($request->only('phone', 'password'))) {
            return response()->json([
                'status' => 'error',
                'message' => 'Invalid credentials'
            ], 500);
        }
        $user = User::query()->where('phone', $request['phone'])->first();
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
            'email' => ['required', 'string', 'email', 'exists:users,email'],
        ]);
        

        return response()->json([
            'status' => 'success',
            'message' => 'Password reset link sent successfully'
        ]);
    }
        
}