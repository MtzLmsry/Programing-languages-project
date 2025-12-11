<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Governorate;

class GovernorateSeeder extends Seeder
{
    public function run(): void
    {
        $governorates = [
            'Damascus',
            'Damascus countryside',
           
        ];

        foreach ($governorates as $name) {
            Governorate::create(['name' => $name]);
        }
    }
}