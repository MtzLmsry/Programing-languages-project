<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreApartmentRequest;
use App\Http\Requests\UpdateApartmentRequest;
use Illuminate\Http\Request;
use App\Models\Apartment;
use App\Models\Apartment_photo;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;

class ApartmentController extends Controller
{
    // GET /apartments
    public function index(Request $request)
    {
        return Apartment::all();
       
    }
    //filtering
    public function search(Request $request)
    {
        $filters = $request->only([
            'city_id', 'governorate_id', 'min_price', 'max_price',
            'rooms', 'apartment_type', 'is_furnished'
        ]);
        $apartments = Apartment::filter($filters)->paginate(10);

        return response()->json([
            'status' => 'success',
            'data' => $apartments
        ]);
    }

    public function store(StoreApartmentRequest $request)
{
   

   

    $user_id = auth()->id(); 
    $apartment = Apartment::create([
        'owner_id' => $user_id, 
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
        $path = $image->store('apartments', 'public');

        Apartment_photo::create([
            'apartment_id' => $apartment->id,
            'photo_url' => $path
        ]);
    }

    return response()->json([
        'status' => 'success',
        'data' => $apartment,
        'message' => 'Apartment submitted and awaiting admin approval'
    ], 201);
}

    // GET /apartments/{id}
    public function show($id)
    {
        $apartment = Apartment::with('images')->find($id);

        if (!$apartment) {
            return response()->json([
                'status' => 'error',
                'message' => 'Apartment not found'
            ], 404);
        }

        return response()->json([
            'status' => 'success',
            'data' => $apartment
        ]);
    }

    // PUT /apartments/{id}/approve
    public function approve($id)
    {
        $apartment = Apartment::find($id);

        if (!$apartment) {
            return response()->json(['status' => 'error', 'message' => 'Not found'], 404);
        }

        $apartment->update([
            'status' => 'approved',
            'reject_reason' => null
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Apartment Approved'
        ]);
    }

    // PUT /apartments/{id}/reject
    public function reject(Request $request, $id)
    {
        $request->validate(['reject_reason' => 'required|string']);

        $apartment = Apartment::find($id);

        if (!$apartment) {
            return response()->json(['status' => 'error', 'message' => 'Not found'], 404);
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

    // PUT /apartments/{id}
    public function update(UpdateApartmentRequest $request, $id)
    {
        $apartment = Apartment::find($id);
        if (!$apartment) {
            return response()->json(['status' => 'error', 'message' => 'Not found'], 404);
        }

       

        $apartment->update($request->except('images'));

        if ($request->hasFile('images')) {

            foreach ($apartment->images as $img) {
                Storage::disk('public')->delete($img->photo_url);
                $img->delete();
            }

            foreach ($request->file('images') as $img) {
                $path = $img->store('apartments', 'public');
                Apartment_photo::create([
                    'apartment_id' => $apartment->id,
                    'photo_url' => $path
                ]);
            }
        }

        return response()->json([
            'status' => 'success',
            'data' => $apartment,
            'message' => 'Updated successfully'
        ]);
    }

    // DELETE /apartments/{id}
    public function destroy($id)
    {
        $apartment = Apartment::find($id);
        if (!$apartment) {
            return response()->json([
                'status' => 'error',
                 'message' => 'Not found'],
                  404);}

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