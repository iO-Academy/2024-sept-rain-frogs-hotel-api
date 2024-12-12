<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Room;
use App\Services\AverageStayService;
use Carbon\Carbon;
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
//         $averageBookings = AverageStayService::getAverageStay();

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
