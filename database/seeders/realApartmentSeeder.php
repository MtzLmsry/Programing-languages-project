<?php

namespace Database\Seeders;

use App\Models\Apartment_photo;
use App\Models\Apartment;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class realApartmentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $owner = User::first();

        $apartments = [
            [
                
                'title' => 'شقة فخمة في المزة',
                'city_id' => 1,
                'governorate_id' => 1,
                'price' => 600,
                'rooms' => 5,
                'floor_number' => 3,
                'apartment_type' => 'multipul_rooms',
                'description' => 'شقة واسعة ومفروشة بالكامل تقع في منطقة المزة الراقية، تحتوي على جميع وسائل الراحة الحديثة.',
                'is_internet_available' => true,
                'is_air_conditioned' => true,
                'is_cleaning_available' => true,
                'is_electricity_available' => true,
                'is_furnished' => true,

                'area'  => 150,
                'images' => [
                    'apartments/apt1_1.jpg',
                    'apartments/apt1_2.jpg',
                    'apartments/apt1_3.jpg',
                    'apartments/apt1_4.jpg',
                    'apartments/apt1_5.jpg',
                ],
            ],
            [
                'city_id' => 1,
                'governorate_id' => 1,
                'title' => 'شقة عائلية في كفرسوسة',
                'price' => 450,
                'rooms' => 5,
                'floor_number' => 2,
                'apartment_type' => 'multipul_rooms',
                'description' => 'شقة مريحة للعائلات تقع في منطقة كفرسوسة، تحتوي على غرف واسعة ومطبخ مجهز بالكامل.',
                'is_internet_available' => true,
                'is_air_conditioned' => true,
                'is_cleaning_available' => false,
                'is_electricity_available' => true,
                'is_furnished' => true,
                'area'  => 120,
                'images' => [
                    'apartments/apt2_1.jpg',
                    'apartments/apt2_2.jpg',
                    'apartments/apt2_3.jpg',
                    'apartments/apt2_4.jpg',
                    'apartments/apt2_5.jpg',
                ],
            ],
            [
                'city_id' => 1,
                'governorate_id' => 1,
                'title' => 'شقة اقتصادية في ركن الدين',
                'price' => 300,
                'rooms' => 6,
                'floor_number' => 1,
                'apartment_type' => 'multipul_rooms',
                'description' => 'شقة اقتصادية تقع في منطقة ركن الدين، مناسبة للطلاب أو العائلات الصغيرة، تحتوي على غرف متعددة ومساحات معيشة بسيطة.',
                'is_internet_available' => false,
                'is_air_conditioned' => false,
                'is_cleaning_available' => false,
                'is_electricity_available' => true,
                'is_furnished' => false,
                'area'  => 120,
                'images' => [
                    'apartments/apt3_1.jpg',
                    'apartments/apt3_2.jpg',
                    'apartments/apt3_3.jpg',
                    'apartments/apt3_4.jpg',
                    'apartments/apt3_5.jpg',
                    'apartments/apt3_6.jpg',
                ],
            ],
        ];

        foreach ($apartments as $data) {
            $apartment = Apartment::create([
                'owner_id' => $owner->id,
                'city_id' => $data['city_id'],
                'governorate_id' => $data['governorate_id'],
                'description' => $data['description'],
                'floor_number' => $data['floor_number'],
                'apartment_type' => $data['apartment_type'],
                'is_internet_available' => $data['is_internet_available'],
                'is_air_conditioned' => $data['is_air_conditioned'],
                'is_cleaning_available' => $data['is_cleaning_available'],
                'is_electricity_available' => $data['is_electricity_available'],
                'is_furnished' => $data['is_furnished'],

                'title'    => $data['title'],
                'price'    => $data['price'],
                'rooms'    => $data['rooms'],
                'area'     => $data['area'],
                'status'   => 'approved',
                'reject_reason' => null,
            ]);

            foreach ($data['images'] as $image) {
                Apartment_photo::create([
                    'apartment_id' => $apartment->id,
                    'photo_url' => $image,
                ]);
            }
        }

        $room = Apartment::create([
            'owner_id' => $owner->id,
            'city_id' => 1,
            'governorate_id' => 1,
            'title'    => 'غرفة طلابية مفروشة',
            'price'    => 150,
            'rooms' => 1,
            'floor_number' => 2,
            'apartment_type' => 'one_room',
            'description' => 'غرفة طلابية مفروشة تقع في منطقة هادئة وقريبة من الجامعات، تحتوي على سرير ومكتب ودولاب ملابس.',
            'is_internet_available' => true,
            'is_air_conditioned' => false,
            'is_cleaning_available' => false,
            'is_electricity_available' => true,
            'is_furnished' => true,
            'area'     => 100,
            'status'   => 'approved',
            'reject_reason' => null,
        ]);

        $roomImages = [
            'rooms/room1_1.jpg',
            'rooms/room1_2.jpg',
        ];

        foreach ($roomImages as $image) {
            Apartment_photo::create([
                'apartment_id' => $room->id,
                'photo_url' => $image,
            ]);
    }
}
}
