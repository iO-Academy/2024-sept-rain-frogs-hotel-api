<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Room;
use Illuminate\Http\Request;

class BookingAPIController extends Controller
{
    public function index()
    {
        $currentAndFutureBookings = Booking::with('room:id,name')
            ->where('end', '>', now())
            ->get()?->makeHidden(['updated_at', 'room_id'])
            ->sortBy('start');
            return response()->json([
            'message' => 'Booking retrieved successfully.',
            'success' => true,
            'data' => $currentAndFutureBookings
        ]);
    }

         public function report()
     {
         $roomsWithBookings = Room::with('booking')
         ->get()?->makeHidden(['rate', 'image', 'min_capacity', 'max_capacity', 'description', 'type_id', 'created_at',
                 'updated_at', 'booking']);

             $bookingCount = $roomsWithBookings->map(function ($room) {
                 return ['room' => $room, 'booking_count' => $room->booking->count()];
             });

         return response()->json([
             'message' => 'Report generated',
             'booking count' => $bookingCount,
             'data' => [$roomsWithBookings, $bookingCount]
         ]);
     }
}
