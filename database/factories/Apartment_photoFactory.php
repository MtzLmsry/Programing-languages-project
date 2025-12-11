<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Apartment;
use App\Models\Apartment_photo;

class Apartment_photoFactory extends Factory
{
    public function definition(): array
    {
        return [
            'apartment_id' => Apartment::inRandomOrder()->first()->id,
            'photo_url' => 'apartments/default.jpg'
        ];
    }
}