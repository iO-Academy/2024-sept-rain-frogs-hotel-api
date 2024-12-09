<?php

use App\Http\Controllers\RoomAPIController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});
