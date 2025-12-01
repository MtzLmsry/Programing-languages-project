<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use App\Models\User;
use Illuminate\Support\Facades\Auth;


class AuthController extends Controller
{
    public function register(Request $request): JsonResponse {
        $request->validate([
            'first_name' => ['required', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
            'phone' => ['required', 'string', 'digits:10', 'unique:users'],
            //'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8'],
            'role'=> ['required','string','in:admin,user'],
            'birth_date' => ['required', 'date'],
            'personal_photo' => ['required', 'image'],
            'id_photo_front' => ['required', 'image'],
            'id_photo_back' => ['required', 'image'],
        ]);

        $user = User::create([
            'first_name' => $request['first_name'],
            'last_name' => $request['last_name'],
            'phone' => $request['phone'],
            //'email' => $request['email'],
            'password' => bcrypt($request['password']),
            'role' => $request['role'],
            'birth_date' => $request['birth_date'],
            'personal_photo' => $request->file('personal_photo')->store('user/personal', 'public'),
            'id_photo_front' => $request->file('id_photo_front')->store('users/id', 'public'),
            'id_photo_back' => $request->file('id_photo_back')->store('users/id', 'public'),
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

    public function login(Request $request): JsonResponse {
        $request->validate([
            'phone' => ['required', 'string', 'digits:10'],
            'password' => ['required', 'string'],
        ]);

        $user = User::where('phone', $request->phone)->first();
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