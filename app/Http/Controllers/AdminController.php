<?php

namespace App\Http\Controllers;

use App\Models\Admin;
use App\Models\User;
use App\Models\Apartment;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AdminController extends Controller
{

    public function login(Request $request): JsonResponse {
        
        
        $request->validate([
            'userName' => 'required|string',
            'password' => 'required|string',
        ]);

      $admin = Admin::where('username', $request->username)->first();

    if (!$admin || !Hash::check($request->password, $admin->password)) {
        return response()->json([
            'status' => 'error',
            'message' => 'Invalid credentials'
        ], 401);
    }

    $token = $admin->createToken('ADMIN_TOKEN')->plainTextToken;

    return response()->json([
        'status' => 'success',
        'data' => [
            'token' => $token,
            'admin' => $admin
        ],
        'message' => 'Admin logged in successfully'
    ]);
    }

    /*
    |-----------------------------------
    | Users Section
    |-----------------------------------
    */

    // GET /admin/users/pending
    public function pendingUsers()
    {
        $users = User::where('account_status', 'Inactive')->get();

        return response()->json([
            'status' => 'success',
            'data' => $users
        ]);
    }

    // POST /admin/users/{id}/approve
    public function approveUser($id)
    {
        $user = User::find($id);

        if (!$user) {
            return response()->json(['message' => 'User not found'], 404);
        }

        $user->update([
            'account status' => 'Active'
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'User approved successfully'
        ]);
    }

    // POST /admin/users/{id}/reject
    public function rejectUser(Request $request, $id)
    {
        $user = User::find($id);

        if (!$user) {
            return response()->json(['message' => 'User not found'], 404);
        }

        $user->update([
            'account status' => 'Inactive',
            'rejection_reason' => $request->rejection_reason,
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'User rejected'
        ]);
    }

    /*
    |-----------------------------------
    | Apartments Section
    |-----------------------------------
    */

    // GET /admin/apartments/pending
    public function pendingApartments()
    {
        $apartments = Apartment::where('status', 'pending')
            ->with(['owner', 'city', 'governorate'])
            ->get();

        return response()->json([
            'status' => 'success',
            'data' => $apartments
        ]);
    }

    // POST /admin/apartments/{id}/approve
    public function approveApartment($id)
    {
        $apartment = Apartment::find($id);

        if (!$apartment) {
            return response()->json(['message' => 'Apartment not found'], 404);
        }

        $apartment->update([
            'status' => 'approved',
            'reject_reason' => null
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Apartment approved'
        ]);
    }

    // POST /admin/apartments/{id}/reject
    public function rejectApartment(Request $request, $id)
    {
        $request->validate([
            'reject_reason' => 'required|string'
        ]);

        $apartment = Apartment::find($id);

        if (!$apartment) {
            return response()->json(['message' => 'Apartment not found'], 404);
        }

        $apartment->update([
            'status' => 'rejected',
            'reject_reason' => $request->reject_reason
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Apartment rejected'
        ]);
    }
}