<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ApartmentController;
use App\Http\Controllers\LocationController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

// Authentication api Routes
Route::controller(AuthController::class)->group(function () {
    Route::post('/register', 'register')->name('auth.register');
    Route::post('/login', 'login')->name('auth.login');
    Route::post('/logout', 'logout')->middleware('auth:sanctum')->name('auth.logout');
});


// Apartments api routes will be here

Route::controller(ApartmentController::class)->group(function (){
    Route::get('/apartments', 'index')->name('apartments.index');
    Route::get('/apartments/search', 'search')->name('apartments.search');
    Route::post('/apartments/create', 'store')->name('apartments.store');
    Route::get('/apartments/show/{id}', 'show')->name('apartments.show');
    Route::put('/apartments/update/{id}', 'update')->name('apartments.update');
    Route::delete('/apartments/delete/{id}', 'destroy')->name('apartments.destroy');
    Route::post('/apartment/{id}/approve', 'approve')->name('apartments.approve');
    Route::post('/apartment/{id}/reject', 'reject')->name('apartments.reject');
})->middleware('auth:sanctum');


Route::controller(LocationController::class)->group(function () {
    Route::get('/governorates', 'getGovernorates')->name('locations.governorates');
    Route::get('/governorates/{id}/cities', 'getCitiesByGovernorate')->name('locations.cities_by_governorate');
});