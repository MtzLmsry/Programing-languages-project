<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ApartmentController;
use App\Http\Controllers\LocationController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\ReviewController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

// Authentication api Routes

Route::controller(AuthController::class)
    ->group(function () {
        Route::post('/register', 'register')->name('auth.register');
        Route::post('/verify-otp', 'verifyOtp')->name('auth.verifyOtp');
        Route::post('/login', 'login')->name('auth.login');
        Route::post('/logout', 'logout')->middleware('auth:sanctum')->name('auth.logout');
        Route::post('/password/forgot', 'forgotPassword')->name('auth.forgotPassword');
        Route::post('/password/reset', 'resetPassword')->name('auth.resetPassword');
});

Route::get('/test-whatsapp', function () {
    $result = sendWhatsAppMessage(
        '+963958429644',
        'اختبار OTP من Laravel '
    );

    return response()->json([
        'success' => $result
    ]);
});


// Apartments api routes will be here

Route::controller(ApartmentController::class)
    ->middleware(['auth:sanctum','active.account'])
    ->group(function (){
    
        Route::get('/apartments/search', 'search')->name('apartments.search');
        Route::post('/apartments/create', 'store')->name('apartments.store');
        Route::put('/apartments/update/{id}', 'update')->name('apartments.update');
        Route::delete('/apartments/delete/{id}', 'destroy')->name('apartments.destroy');
});

Route::controller(ApartmentController::class)
    ->group(function () {

        Route::get('/apartments', 'index')->name('apartments.index');
        Route::get('/apartments/show/{id}', 'show')->name('apartments.show');
});

Route::controller(LocationController::class)
    ->group(function () {

        Route::get('/governorates', 'getGovernorates')->name('locations.governorates');
        Route::get('/governorates/{id}/cities', 'getCitiesByGovernorate')->name('locations.cities_by_governorate');

});

//Admin apis

Route::controller(AdminController::class)
    ->middleware('auth:sanctum')
    ->group(function () {
    
        Route::get('/users/pending',  'pendingUsers');
        Route::post('/users/{id}/approve', 'approveUser');
         Route::post('/users/{id}/reject', 'rejectUser');
         Route::get('/apartments/pending',  'pendingApartments');
         Route::post('/apartments/{id}/approve', 'approveApartment');
         Route::post('/apartments/{id}/reject',  'rejectApartment');

});
Route::post('/admin/login', [AdminController::class, 'login'])
->name('admin.login');//also admin api

//to index all users
Route::get('/users', [UserController::class, 'index'])->name('users.index');

//for the BookingController
Route::controller(BookingController::class)
    ->middleware(['auth:sanctum','active.account'])
    ->group(function (){
        Route::get('/bookings', 'index')->name('bookings.index');
        Route::post('/bookings/create', 'store')->name('bookings.store');
        Route::get('/bookings/my', 'showmyBooking')->name('bookings.showmyBooking');
        Route::post('/bookings/update/{id}', 'update')->name('bookings.update');
        Route::get('/bookings/owner', 'ownerBooking')->name('bookings.ownerBooking');
        Route::post('/bookings/cancel/{id}', 'cancel')->name('bookings.destroy');
        //this is for the owner to approve or reject booking requests
        Route::post('/bookings/{id}/approve', 'approve')->name('bookings.approve');
        Route::post('/bookings/{id}/reject', 'reject')->name('bookings.reject');
});


//for the ReviewController
Route::controller(ReviewController::class)
    ->middleware(['auth:sanctum','active.account'])
    ->group(function (){
        Route::post('/reviews/create', 'store')->name('reviews.store');
});
Route::get('/apartments/{apartment_id}/reviews', [ReviewController::class, 'getApartmentReviews'])->name('reviews.apartmentReviews');
