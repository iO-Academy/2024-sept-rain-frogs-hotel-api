<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use Illuminate\Http\Request;

class BookingAPIController extends Controller
{
    public function create(Request $request)
    {
        $request->validate([
            'room_id' => 'required|integer|exists:rooms,id',
            'customer' => 'required|string|max:255',
            'guests' => 'required|integer|min:1',
            'start' => 'required|date|date_format:Y-m-d',
            'end' => 'required|date|date_format:Y-m-d',
        ]);
    $booking = new Booking();

    $booking->room_id = $request->room_id;
    $booking->customer = $request->customer;
    $booking->start = $request->start;
    $booking->end = $request->end;

    $booking->save();

    return response()->json([
        'message' => 'Booking successfully created',
        'data' => $booking
        ], 201);
    }
}
