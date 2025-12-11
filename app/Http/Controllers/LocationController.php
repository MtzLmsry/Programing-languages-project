<?php

namespace App\Http\Controllers;

use App\Models\Governorate;
use App\Models\City;

class LocationController extends Controller
{
    // GET /governorates
    public function getGovernorates()
    {
        return response()->json([
            'status' => 'success',
            'data' => Governorate::all()
        ], 200);
    }

    // GET /governorates/{id}/cities
    public function getCitiesByGovernorate($id)
    {
        $cities = City::where('governorate_id', $id)->get();

        if ($cities->isEmpty()) {
            return response()->json([
                'status' => 'error',
                'message' => 'No cities found for this governorate'
            ], 404);
        }

        return response()->json([
            'status' => 'success',
            'data' => $cities
        ], 200);
    }
}