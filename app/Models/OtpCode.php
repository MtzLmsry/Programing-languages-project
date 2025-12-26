<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OtpCode extends Model
{
    protected $fillable = [
        'user_id',
        'code',
        'type',
        'expires_at',
    ];
    protected $dates = [
        'expires_at',
    ];
}
