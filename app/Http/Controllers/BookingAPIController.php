<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Room;
use App\Services\BookingService;
use Carbon\CarbonPeriod;
use http\Env\Response;
use Illuminate\Http\Request;

class BookingAPIController extends Controller
{
    public function create(Request $request)
    {
        $request->validate([
            'room_id' => 'required|integer|exists:rooms,id',
            'customer' => 'required|string|max:255',
            'guests' => 'required|integer|min:1',
            'start' => 'required|date|date_format:Y-m-d|after_or_equal:today',
            'end' => 'required|date|date_format:Y-m-d|after:start',
        ]);

        $room = Room::find($request->room_id);

        if (BookingService::checkCapacity($room, $request)) {
            return response()->json([
                'message' => "The {$room->name} room can only accommodate between {$room->min_capacity} and {$room->max_capacity} guests",
            ],400);
        }

        if (BookingService::dateConflict($room, $request->start, $request->end )) {
                return response()->json([
                    'message' => "The {$room->name} room is unavailable for the chosen dates."
                ], 400);
        }

        $booking = new Booking();

        $booking->room_id = $request->room_id;
        $booking->customer = $request->customer;
        $booking->start = $request->start;
        $booking->end = $request->end;

        $booking->save();

        return response()->json([
            'message' => "Booking successfully created"
        ], 201);
    }
}
