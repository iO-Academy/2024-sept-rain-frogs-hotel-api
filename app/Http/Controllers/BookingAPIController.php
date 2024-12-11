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
            ->map(function ($booking) {
                $booking->room->makeHidden(['id']);
                return $booking;
            })
            ->sortBy('start');
            return response()->json([
            'message' => 'Booking retrieved successfully.',
            'success' => true,
            'data' => $currentAndFutureBookings
        ]);
    }
//     public function report()
//     {
//         $roomsWithBookings = Room::with('bookings:id,name')->get();
//         return response()->json([
//             'message' => 'Booking retrieved successfully.',
//             'success' => true,
//             'data' => $roomsWithBookings
//         ]);
//     }

}
