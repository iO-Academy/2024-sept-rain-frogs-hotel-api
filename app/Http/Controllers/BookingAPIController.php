<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Room;
use App\Services\BookingService;
use Illuminate\Http\Request;

class BookingAPIController extends Controller
{
    public function index(Request $request)
    {
        $request->validate([
            'room_id' => 'nullable|exists:rooms,id'
        ]);

        $query = Booking::query();

        if ($request->has('room_id')) {
            $currentAndFutureBookings = $query->where('room_id', $request->room_id)
                ->with('room:id,name')
                ->where('end', '>', now())
                ->get()?->makeHidden(['updated_at', 'room_id']);

            return response()->json([
                'message' => 'Booking retrieved successfully.',
                'success' => true,
                'data' => $currentAndFutureBookings
            ]);
        }

        $currentAndFutureBookings = Booking::with('room:id,name')
            ->where('end', '>', now())
            ->orderBy('start')
            ->get()?->makeHidden(['updated_at', 'room_id']);

        return response()->json([
            'message' => 'Booking retrieved successfully.',
            'success' => true,
            'data' => $currentAndFutureBookings
        ]);
    }

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
  
    public function delete(int $id)
    {
        $booking = Booking::find($id);

        if(!$booking){
            return response()->json([
                "message" => "Unable to cancel booking, booking $id not found"
            ],404);
        }

        $booking->delete();
      
        return response()->json([
            "message"=>"Booking $id cancelled"
        ]);
    }
}