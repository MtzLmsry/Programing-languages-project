<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Apartment;
use App\Models\User;
use \App\Models\Apartment_photo;
use App\Models\Governorate;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        

    $this->call([
        GovernorateSeeder::class,
        CitySeeder::class,
        AdminSeeder::class,
    ]);

    User::factory(10)->create();

    Apartment::factory(20)->create();

    }
}