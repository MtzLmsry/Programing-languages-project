<?php

namespace Database\Factories;

use App\Models\Apartment;
use App\Models\User;
use App\Models\City;
use App\Models\Governorate;
use Illuminate\Database\Eloquent\Factories\Factory;

class ApartmentFactory extends Factory
{
    protected $model = Apartment::class;

    public function definition(): array
    {
        $type = $this->faker->randomElement(['one_room', 'multipul_rooms']);

        if ($type === 'one_room') {
            $rooms = 1;
            $area  = $this->faker->numberBetween(30, 80);
        } else {
            $rooms = $this->faker->numberBetween(2, 5);
            $area  = $this->faker->numberBetween(90, 200);
        }

        return [
            'owner_id' => User::inRandomOrder()->first()->id,
            'city_id' => City::inRandomOrder()->first()->id,
            'governorate_id' => Governorate::inRandomOrder()->first()->id,
            'title' => $this->faker->sentence(3),
            'price' => $this->faker->numberBetween(100, 1000),
            'rooms' => $rooms,
            'floor_number' => $this->faker->numberBetween(0, 5),
            'area' => $area,
            'apartment_type' => $type,
            'description' => $this->faker->paragraph(),
            'is_internet_available' => $this->faker->boolean(),
            'is_air_conditioned' => $this->faker->boolean(),
            'is_cleaning_available' => $this->faker->boolean(),
            'is_electricity_available' => true,
            'is_furnished' => $this->faker->boolean(),
            'status' => 'approved',
        ];
    }

    public function configure()
    {
        return $this->afterCreating(function (Apartment $apartment) {
            \App\Models\Apartment_photo::factory()
                ->count(rand(4, 6))         
                ->create([
                    'apartment_id' => $apartment->id
                ]);
        });
    }
}