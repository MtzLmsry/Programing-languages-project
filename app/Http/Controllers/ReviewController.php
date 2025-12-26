<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Review;
use App\Models\Booking;
class ReviewController extends Controller
{
    public function store (Request $request){
        $request->validate([
            'booking_id' => 'required|exists:bookings,id',
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'required|string|max:1000',
        ]);

        $user = Auth::user();

        $booking = Booking::find($request->booking_id)
        ->where('user_id', $user->id)
        ->where('status', 'confirmed')
        ->first();

        if(!$booking){
            return response()->json(['message' => 'Invalid booking or booking not confirmed'], 400);
        }
       $exists = Review::where('booking_id', $booking->id)->exists();

       if($exists){
        return response()->json(['message' => 'You have already reviewed this booking'], 400);
       }

        $review = Review::create([
            'user_id' => $user->id,
            'apartment_id' => $booking->apartment_id,
            'booking_id' => $booking->id,
            'rating' => $request->rating,
            'comment' => $request->comment,
        ]);

        return response()->json(['message' => 'Review submitted successfully', 'review' => $review], 201);
    }   
    
    public function getApartmentReviews($apartment_id){
        $reviews = Review::where('apartment_id', $apartment_id)
        ->with('user:id, FirstName, LastName')
        ->latest()
        ->get();

        return response()->json(['reviews' => $reviews], 200);
    }
}
