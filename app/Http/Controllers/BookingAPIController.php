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
         $roomsWithBookings = Booking::with('room')->get();
         $total = 0;
         $bookingCount = $roomsWithBookings->count();

             foreach ($roomsWithBookings as $room) {
                 $bookingStartDate = strtotime($room->start);
                 $bookingEndDate = strtotime($room->end);

                 $bookingDuration =    $bookingEndDate - $bookingStartDate;
                 $bookingDurationInDays = $bookingDuration/ (60 * 60 * 24);
                 $total += $bookingDurationInDays;

             }
             $result = round($total / $bookingCount,1);

         return response()->json([
             'message' => 'Bookings retrieved successfully.',
             'success' => true,
             'data' => $result
         ]);
     }
}
