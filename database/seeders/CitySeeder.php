<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\City;
use app\Models\Governorate;
class CitySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
           $cities = [
            'Damascus' => ['Saroujah', 'Mezzeh', 'Kafr Souseh','Al-Malkie','Ab-Romaneh','Al-Medan','Bab Touma', 'Salheh', 'Al-Baramkeh'],
            'Damascus countryside' => ['At-Tal', 'Mneen', 'Sednaya', 'Douma','Harasta','Halbon', 'AlKesoyeh'],
        ];

        foreach ($cities as $governorate => $cityList) {
            $gov = Governorate::where('name', $governorate)->first();

            foreach ($cityList as $city) {
                City::create([
                    'governorate_id' => $gov->id,
                    'name' => $city
                ]);
            }
        }
    }
}
