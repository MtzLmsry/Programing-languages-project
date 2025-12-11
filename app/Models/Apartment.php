<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Apartment extends Model
{
    use HasFactory;

    protected $fillable = [
        'owner_id',
        'city_id',
        'governorate_id',
        'title',
        'price',
        'rooms',
        'floor_number',
        'area',
        'apartment_type',
        'description',
        'is_internet_available',
        'is_air_conditioned',
        'is_cleaning_available',
        'is_electricity_available',
        'is_furnished',
        'status',
        'reject_reason'

    ];

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

    public function scopeFilterArea($query, $minArea, $maxArea)
    {
        return $query->whereBetween('area', [$minArea, $maxArea]);
    }

    public function scopeFilterRooms($query, $rooms)
    {
        return $query->where('rooms', $rooms);
    }

    public function scopeFilteris_Furnished($query, $furnished)
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

        if(isset($filters['min_area']) && isset($filters['max_area'])){
            $query->filterArea($filters['min_area'], $filters['max_area']);
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