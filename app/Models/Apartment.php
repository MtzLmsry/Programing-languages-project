<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Apartment extends Model
{
    protected $guarded = [];

    /*
    |----------------------|
    | Relationships        | 
    |----------------------|
    */
    public function owner()
    {
        return $this->belongsTo(User::class, 'owner_id');
    }

    public function city()
    {
        return $this->belongsTo(City::class);
    }

    public function governorate()
    {
        return $this->belongsTo(Governorate::class);
    }

    public function images()
    {
        return $this->hasMany(Apartment_photo::class);
    }

    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }

    
    /*
    |----------------------|
    | Filters (Scopes)     |
    |----------------------|
    */

    public function scopeFilterGovernorate($query, $governorateId)
    {
        return $query->where('governorate_id', $governorateId);
    }

    public function scopeFilterCity($query, $cityId)
    {
        return $query->where('city_id', $cityId);
    }

    public function scopeFilterPrice($query, $min, $max)
    {
        return $query->whereBetween('price', [$min, $max]);
    }

    public function scopeFilterRooms($query, $rooms)
    {
        return $query->where('rooms', $rooms);
    }

    public function scopeFilterFurnished($query, $furnished)
    {
        return $query->where('is_furnished', $furnished);
    }

    public function scopeFilterSearch($query, $search)
    {
        return $query->where(function ($q) use ($search) {
            $q->where('title', 'like', "%$search%")
              ->orWhere('description', 'like', "%$search%");
        });
    }

    
    /*
    |-----------------------------|
    | Main Scope (Combined Filter)|
    |-----------------------------|
    */
    
    public function scopeFilter($query, $filters)
    {
        if (isset($filters['governorate_id'])) {
            $query->filterGovernorate($filters['governorate_id']);
        }

        if (isset($filters['city_id'])) {
            $query->filterCity($filters['city_id']);
        }

        if (isset($filters['min_price']) && isset($filters['max_price'])) {
            $query->filterPrice($filters['min_price'], $filters['max_price']);
        }

        if (isset($filters['rooms'])) {
            $query->filterRooms($filters['rooms']);
        }

        if (isset($filters['furnished'])) {
            $query->filterFurnished($filters['furnished']);
        }

        if (isset($filters['search'])) {
            $query->filterSearch($filters['search']);
        }

        return $query;
    }
}