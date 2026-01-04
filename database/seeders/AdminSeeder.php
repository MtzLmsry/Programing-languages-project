<?php

namespace Database\Seeders;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Admin;
use App\Models\User;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
       User::create([
            'FirstName' => 'Admin',
            'LastName' => 'User',
            'phone' => '+963959493837',
            'password' => Hash::make('Hh123456789'),
            'Birthdate' => '1990-01-01',
            'account_status' => 'Active',
            'personalPhoto' => 'path/to/personal/photo.jpg',
            'idPhotoFront' => 'path/to/id/front.jpg',
            'idPhotoBack' => 'path/to/id/back.jpg',
        ]);
    }
}
