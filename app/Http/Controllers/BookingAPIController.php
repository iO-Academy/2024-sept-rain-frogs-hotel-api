<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Room;
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
            'start' => 'required|date|date_format:Y-m-d',
            'end' => 'required|date|date_format:Y-m-d',
        ]);
        $room = Room::find($request->room_id);
        $bookingConflict = Booking::where('room_id', $room->id)
            ->where('start', '>=', $request->start);



        $startDate = $request->start;
        $endDate = $request->end;
            if ($room->min_capacity > $request->guests OR  $room->max_capacity < $request->guests) {
                return response()->json([
                    'message' => "The {$room->name} room can only accommodate between {$room->min_capacity} and {$room->max_capacity} guests",
                ]);
            }
            elseif ($endDate < $startDate)
            {
                return response()->json([
                   'message' => 'Start date must be before the end date'
                ], 400);
            }
            elseif($bookingConflict->exists())
            {
                if()
                {

                }
                return response()->json([
                    'message' => "The {$room->name} room is unavailable for the chosen dates"
                ], 400);

//             check room_id -> check if the room is booked at any point
//                 if the room has been booked -> check the start and end dates
//                'booking conflict' => $bookingConflict->first()

            }
            else
            {
                $booking = new Booking();

                $booking->room_id = $request->room_id;
                $booking->customer = $request->customer;
                $booking->start = $request->start;
                $booking->end = $request->end;

                $booking->save();

                return response()->json([
                    'message' => 'Booking successfully created',
                    'data' => $booking->room->min_capacity
                    ], 201);
            }
    }
}
