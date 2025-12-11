<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ApartmentController;
Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

// Authentication api Routes
Route::post('/register',[AuthController::class,'register' ])->name("Register");

Route::post('/login',[AuthController::class,'login' ])->name("Login");

Route::middleware('auth:sanctum')->group(function(){
    Route::post('/logout',[AuthController::class,'logout' ]);
})->name("Logout");

// Apartments api routes will be here

Route::middleware('auth:sanctum')->group(function(){
    
    Route::post('/apartments/index', [ApartmentController::class, 'index'])->name("indexAllApartment");                            
    
    //*******************************************************************************************/
    /* update an apartment                                                                      */ 
    Route::post('/apartments/add', [ApartmentController::class, 'store'])->name("addApartment");      

    Route::post('/apartment/update/{id}', [ApartmentController::class, 'update'])->name("updateApartment");
    Route::get('/apartment/search', [ApartmentController::class, 'search'])->name("searchApartment");

    Route::get('/apartment/show/{id}', [ApartmentController::class, 'show'])->name("showApartment");
    //*******************************************************************************************/
    /* delete an apartment                                                                      */
    Route::delete('/apartment/delete/{id}', [ApartmentController::class, 'destroy'])->name("deleteApartment");
    //*******************************************************************************************/
    /* aprove or reject apartment (admin access only)                                           */
    Route::post('/apartment/{id}/approve', [ApartmentController::class, 'approve'])->name("aprrove"); //apreove
    //*******************************************************************************************/
    Route::post('/apartment/{id}/reject', [ApartmentController::class, 'reject'])->name("reject");   //reject   
    //*******************************************************************************************/
});
