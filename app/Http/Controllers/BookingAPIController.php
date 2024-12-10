<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Room;
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
        $room = Room::find($request->room_id);
            if ($room->min_capacity < $request->guests) {
                return response()->json([
                    'message' => "The {$room->name} room can only accommodate between {$room->min_capacity} and {$room->max_capacity} guests",
                ]);
            }
            else
                {
                return 'GREAT SUCCESS';
                    }

    $booking = new Booking();

    $booking->room_id = $request->room_id;
    $booking->customer = $request->customer;
    $booking->start = $request->start;
    $booking->end = $request->end;

    $booking->save();

    return response()->json([
        'message' => 'Booking successfully created',
        'data' => $booking->room->min_capacity,
        ], 201);
    }
}
