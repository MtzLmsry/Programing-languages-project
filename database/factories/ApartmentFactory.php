<?php

namespace Database\Factories;

use App\Models\Apartment;
use App\Models\User;
use App\Models\City;
use App\Models\Governorate;
use App\Models\Apartment_photo;
use Illuminate\Database\Eloquent\Factories\Factory;

class ApartmentFactory extends Factory
{
    protected $model = Apartment::class;

    public function definition()
    {
        return [
            'owner_id' => User::inRandomOrder()->first()->id ?? User::factory(),
            'city_id' => City::inRandomOrder()->first()->id,
            'governorate_id' => Governorate::inRandomOrder()->first()->id,
            'title' => $this->faker->sentence(),
            'price' => $this->faker->numberBetween(100, 700),
            'rooms' => $this->faker->numberBetween(1,5),
            'floor_number' => $this->faker->numberBetween(1,10),
            'area' => $this->faker->numberBetween(40,200),
            'apartment_type' => $this->faker->randomElement(['one_room','multipul_rooms']),
            'description' => $this->faker->paragraph(),
            'is_internet_available' => $this->faker->boolean(),
            'is_air_conditioned' => $this->faker->boolean(),
            'is_cleaning_available' => $this->faker->boolean(),
            'is_electricity_available' => $this->faker->boolean(),
            'is_furnished' => $this->faker->boolean(),
            'status' => 'approved',
        ];
    }
    public function configure()
    {
        return $this->afterCreating(function (Apartment $apartment) {
            Apartment_photo::factory(4)->create([
                'apartment_id' => $apartment->id
            ]);
        });
    }
}