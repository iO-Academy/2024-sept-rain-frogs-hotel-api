<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Room;
use Carbon\Carbon;
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

         public function report()
     {
             $reportData = [];
             // We add this at the top of the method in order to create an empty array (which
             // we will fill with the required variables

         $roomsWithBookings = Room::with('booking')
         ->get()?->makeHidden(['rate', 'image', 'min_capacity', 'max_capacity', 'description', 'type_id', 'created_at',
                 'updated_at', 'booking']);

         foreach ($roomsWithBookings as $roomWithBooking) {
             $roomBookingCount = $roomWithBooking->booking->count();
             $roomName = $roomWithBooking->name;
             $roomId = $roomWithBooking->id;
             $total = 0;

             foreach ($roomWithBooking->booking as $booking) {
                 $checkIn = Carbon::parse($booking['start']);
                 $checkOut = Carbon::parse($booking['end']);

                 $total += $checkIn->diffInDays($checkOut);
             }

             $averageStay = $roomBookingCount > 0 ? $total / $roomBookingCount : 0;
             // We are accessing the variables and adding them into the empty array
             $reportData[] = [
                 'id' => $roomId,
                 'name' => $roomName,
                 'booking_count' => $roomBookingCount,
                 'average_booking_duration' => round($averageStay, 1),
             ];
         }

         return response()->json([
             'message' => 'Report generated',
             'data' => $reportData
         ]);
     }
}
