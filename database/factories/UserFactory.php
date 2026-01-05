<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;

class UserFactory extends Factory
{
    public function definition(): array
    {
        return [
            'FirstName' => fake()->firstName(),
            'LastName' => fake()->lastName(),
            'phone' => fake()->unique()->numerify('+9639########'),
            'password' => Hash::make('password'),
            'BirthDate' => fake()->date(),
            'PersonalPhoto' => 'default_user.jpg',
            'idPhotoFront' => 'default_id.jpg',
            'idPhotoBack' => 'default_id.jpg',
            'account_status' => 'Inactive',
        ];
    }
}