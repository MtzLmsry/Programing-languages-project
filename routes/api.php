<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ApartmentController;
use App\Http\Controllers\LocationController;
use App\Http\Controllers\AdminController;

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

Route::controller(ApartmentController::class)->middleware('auth:sanctum','active.account')->group(function (){
    
    Route::get('/apartments/search', 'search')->name('apartments.search');
    Route::post('/apartments/create', 'store')->name('apartments.store');
    Route::put('/apartments/update/{id}', 'update')->name('apartments.update');
    Route::delete('/apartments/delete/{id}', 'destroy')->name('apartments.destroy');
});

Route::controller(ApartmentController::class)->group(function () {

    Route::get('/apartments', 'index')->name('apartments.index');
    Route::get('/apartments/show/{id}', 'show')->name('apartments.show');
});

Route::controller(LocationController::class)->group(function () {

    Route::get('/governorates', 'getGovernorates')->name('locations.governorates');
    Route::get('/governorates/{id}/cities', 'getCitiesByGovernorate')->name('locations.cities_by_governorate');

});
//Admin apis
Route::controller(AdminController::class)->middleware(['auth:sanctum', 'admin'])->prefix('admin')->group(function () {

    Route::get('/users/pending',  'pendingUsers');
    Route::post('/users/{id}/approve', 'approveUser');
    Route::post('/users/{id}/reject', 'rejectUser');

    Route::get('/apartments/pending',  'pendingApartments');
    Route::post('/apartments/{id}/approve', 'approveApartment');
    Route::post('/apartments/{id}/reject',  'rejectApartment');
});