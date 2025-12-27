<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OtpCode extends Model
{
    protected $fillable = [
        'user_id',
        'phone',
        'code',
        'type',
        'expires_at',
        'is_used',
    ];
    protected $dates = [
        'expires_at' => 'datetime',
        'is_used' => 'boolean',
    ];
}
