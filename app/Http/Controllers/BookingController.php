<?php

namespace App\Http\Controllers;

use App\Http\Requests\storeBookingRequest;
use App\Models\Booking;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\JsonResponse;
class BookingController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return Booking::all();
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(storeBookingRequest $request): JsonResponse{
        $user_id = Auth::id();

        $existingBooking = Booking::where('apartment_id', $request->apartment_id)
            ->where(function ($query) use ($request) {
                $query->whereBetween('start_date', [$request->start_date, $request->end_date])
                      ->orWhereBetween('end_date', [$request->start_date, $request->end_date])
                      ->orWhere(function ($query) use ($request) {
                          $query->where('start_date', '<=', $request->start_date)
                                ->where('end_date', '>=', $request->end_date);
                      });
            })
            ->where('status', 'confirmed')
            ->first();
        if ($existingBooking) {
            return response()->json(['message' => 'The apartment is already booked for the selected dates'], 409);
        }
        $booking = Booking::create([
            'user_id' => $user_id,
            'apartment_id' => $request->apartment_id,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'status' => 'still',
        ]);
        return response()->json([
            'message' => 'Booking created successfully',
            'booking' => $booking
            ], 201);

    }



    /**
     * Display the specified resource.
     */
    public function showmyBooking()
    {
        $bookings = Booking::where('user_id', Auth::id())->get();
        return response()->json([
            'bookings' => $bookings
        ], 200);
    }

    public function update(storeBookingRequest $request, $id)
    {
        $booking = Booking::find($id);
        if (!$booking) {
            return response()->json([
                'message' => 'Booking not found'
            ], 404);
        }

        if($booking->user_id != Auth::id()){
            return response()->json([
                'message' => 'Unauthorized'
            ], 403);
        }

        if($booking->status != 'still'){
            return response()->json([
                'message' => 'Cannot update booking that is not in still status'
            ], 400);
        }
        $conflictingBooking = Booking::where('apartment_id', $request->apartment_id)
            ->where('id', '!=', $id)
            ->where('status', 'confirmed')
            ->where(function ($query) use ($request) {
                $query->whereBetween('start_date', [$request->start_date, $request->end_date])
                      ->orWhereBetween('end_date', [$request->start_date, $request->end_date]);
                      })->exists();

        if ($conflictingBooking) {
            return response()->json([
                'message' => 'The apartment is already booked for the selected dates'
            ], 409);
        }

        $booking->update($request->all());
        return response()->json([
            'message' => 'Booking updated successfully',
            'booking' => $booking
        ], 200);
    }

   public function approve($id){
    $booking = Booking::find($id);
    if (!$booking) {
        return response()->json([
            'message' => 'Booking not found'
        ], 404);
    }
    $booking->status = 'confirmed';
    $booking->save();
    return response()->json([
        'message' => 'Booking approved successfully',
        'booking' => $booking
    ], 200);
   }

   public function reject($id){
    $booking = Booking::find($id);
    if (!$booking) {
        return response()->json([
            'message' => 'Booking not found'
        ], 404);
    }
    $booking->status = 'rejected';
    $booking->save();
    return response()->json([
        'message' => 'Booking rejected successfully',
        'booking' => $booking
    ], 200);
   }

   public function cancel($id): JsonResponse{
    $booking = Booking::find($id);
    if (!$booking) {
        return response()->json([
            'message' => 'Booking not found'
        ], 404);
    }

    if($booking->user_id != Auth::id()){
        return response()->json([
            'message' => 'Unauthorized'
        ], 403);
    }
    if(in_array($booking->status, ['canceled', 'rejected'])){
        return response()->json([
            'message' => 'Booking is already canceled or rejected'
        ], 400);
    }
    $booking->update([
        'status' => 'canceled'
    ]);
    
    return response()->json([
        'message' => 'Booking canceled successfully',
        'booking' => $booking
    ], 200);
   }
}
