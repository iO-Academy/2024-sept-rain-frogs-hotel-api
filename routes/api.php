<?php

use App\Http\Controllers\BookingAPIController;
use App\Http\Controllers\RoomAPIController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::get('/rooms', [RoomAPIController::class, 'index']);
Route::get('/rooms/{id}', [RoomAPIController::class, 'find']);

Route::get('/bookings',[BookingAPIController::class,'index']);
Route::post('/bookings', [BookingAPIController::class, 'create']);
Route::delete('/bookings/{id}',[BookingAPIController::class,'delete']);