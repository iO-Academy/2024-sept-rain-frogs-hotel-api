<?php

namespace App\Services;

use App\Models\Booking;

class AverageStayService
{
    public static function getAverageStay()
    {
        $roomsWithBookings = Booking::with('room:id')->get();
        $total = 0;
        $bookingCount = $roomsWithBookings->count();


        foreach ($roomsWithBookings as $room) {
            $bookingStartDate = strtotime($room->start);
            $bookingEndDate = strtotime($room->end);

            $bookingDuration = $bookingEndDate - $bookingStartDate;
            $bookingDurationInDays = $bookingDuration / (60 * 60 * 24);
            $total += $bookingDurationInDays;
        }

        $result = round($total / $bookingCount, 1);


        return response()->json([
            'message' => 'Bookings retrieved successfully.',
            'success' => true,
            'data' => $result
        ]);
    }

}
