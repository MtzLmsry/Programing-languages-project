<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{

    protected $fillable = [
        'apartment_id',
        'user_id',
        'start_date',
        'end_date',
        'status'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function apartment()
    {
        return $this->belongsTo(Apartment::class);
    }
}
