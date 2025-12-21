<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Apartment;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    /*
    |-----------------------------------
    | Users Section
    |-----------------------------------
    */

    // GET /admin/users/pending
    public function pendingUsers()
    {
        $users = User::where('account_status', 'inactive')->get();

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