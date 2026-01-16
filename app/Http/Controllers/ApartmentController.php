<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreApartmentRequest;
use App\Http\Requests\UpdateApartmentRequest;
use Illuminate\Http\Request;
use App\Models\Apartment;
use App\Models\Apartment_photo;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class ApartmentController extends Controller
{
    // GET /apartments (approved only)
    public function indexApprovedApartment()
    {
        return Apartment::where('status', 'approved')
            ->with('images')
            ->get();
    }

    // Filtering
    public function search(Request $request)
    {
        $filters = $request->only([
            'city_id',
            'governorate_id',
            'min_price',
            'max_price',
            'rooms',
            'apartment_type',
            'is_furnished'
        ]);

        $apartments = Apartment::filter($filters)
            ->where('status', 'approved')
            ->with('images')
            ->paginate(10);

        return response()->json([
            'status' => 'success',
            'data' => $apartments
        ]);
    }

    // Store apartment
    public function store(StoreApartmentRequest $request)
    {
        $user = Auth::user();

        // Check if user is blocked
        if ($user->bloocked_until && $user->bloocked_until > now()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Your account is temporarily blocked from submitting apartments.'
            ], 403);
        }

        // Check apartment limit (5 per 24h)
        $apartmentCount = Apartment::where('owner_id', $user->id)
            ->where('created_at', '>=', now()->subDay())
            ->count();

        if ($apartmentCount >= 5) {
            $user->update([
                'account_status' => 'Inactive',
                'bloocked_until' => now()->addDay()
            ]);

            return response()->json([
                'status' => 'error',
                'message' => 'You reached the limit of 5 apartments in 24 hours.'
            ], 429);
        }

        $apartment = Apartment::create([
            'owner_id' => $user->id,
            'city_id' => $request->city_id,
            'governorate_id' => $request->governorate_id,
            'title' => $request->title,
            'price' => $request->price,
            'rooms' => $request->rooms,
            'floor_number' => $request->floor_number,
            'apartment_type' => $request->apartment_type,
            'area' => $request->area,
            'description' => $request->description,
            'is_internet_available' => $request->boolean('is_internet_available'),
            'is_air_conditioned' => $request->boolean('is_air_conditioned'),
            'is_cleaning_available' => $request->boolean('is_cleaning_available'),
            'is_electricity_available' => $request->boolean('is_electricity_available'),
            'is_furnished' => $request->boolean('is_furnished'),
            'status' => 'pending',
        ]);

        foreach ($request->file('images') as $image) {
            Apartment_photo::create([
                'apartment_id' => $apartment->id,
                'photo_url' => $image->store('apartments', 'public')
            ]);
        }

        return response()->json([
            'status' => 'success',
            'data' => $apartment->load('images'),
            'message' => 'Apartment submitted successfully'
        ], 201);
    }

    // Show apartment
    public function show($id)
    {
        $apartment = Apartment::with('images')->find($id);

        if (!$apartment) {
            return response()->json(['message' => 'Apartment not found'], 404);
        }

        return response()->json([
            'status' => 'success',
            'data' => $apartment
        ]);
    }

    // Update
    public function update(UpdateApartmentRequest $request, $id)
    {
        $apartment = Apartment::find($id);

        if (!$apartment) {
            return response()->json(['message' => 'Not found'], 404);
            }

        $apartment->update($request->except('images'));

        if ($request->hasFile('images')) {
            foreach ($apartment->images as $img) {
                Storage::disk('public')->delete($img->photo_url);
                $img->delete();
            }

            foreach ($request->file('images') as $image) {
                Apartment_photo::create([
                    'apartment_id' => $apartment->id,
                    'photo_url' => $image->store('apartments', 'public')
                ]);
            }
        }

        return response()->json([
            'status' => 'success',
            'data' => $apartment->load('images')
        ]);
    }

    // Delete
    public function destroy($id)
    {
        $apartment = Apartment::find($id);

        if (!$apartment) {
            return response()->json(['message' => 'Not found'], 404);
        }

        foreach ($apartment->images as $img) {
            Storage::disk('public')->delete($img->photo_url);
            $img->delete();
        }

        $apartment->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Apartment deleted'
        ]);
    }
}