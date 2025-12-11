<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;

class UserFactory extends Factory
{
    public function definition(): array
    {
        return [
            'first_name' => fake()->firstName(),
            'last_name' => fake()->lastName(),
            'phone' => fake()->unique()->numerify('09########'),
            'password' => Hash::make('password'),
            'role' => 'user',
            'birth_date' => fake()->date(),
            'personal_photo' => 'default_user.jpg',
            'id_photo_front' => 'default_id.jpg',
            'id_photo_back' => 'default_id.jpg',
        ];
    }
}