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
            'start' => 'required|date|date_format:Y-m-d|after_or_equal:today',
            'end' => 'required|date|date_format:Y-m-d|after:start',
        ]);

        $room = Room::find($request->room_id);
        $startDate = $request->start;
        $endDate = $request->end;


        $bookingConflict = Booking::where('room_id', $room->id)
            ->where(function ($query) use ($startDate, $endDate) {
                $query->whereBetween('start', [$startDate, $endDate])
                    ->orWhereBetween('end', [$startDate, $endDate])
                    ->orWhere(function ($query) use ($startDate, $endDate) {
                        $query->where('start', '<=', $startDate)
                            ->where('end', '>=', $endDate);
                    });
            })
            ->exists();

        if ($room->min_capacity > $request->guests OR  $room->max_capacity < $request->guests) {
            return response()->json([
                'message' => "The {$room->name} room can only accommodate between {$room->min_capacity} and {$room->max_capacity} guests",
            ],400);
        }
        elseif ($bookingConflict)
        {
                return response()->json([
                    'message' => "The {$room->name} room is unavailable for the chosen dates."
                ], 400);
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
                'message' => "Booking successfully created",
                'data' => $booking
            ], 201);
        }
    }
}
