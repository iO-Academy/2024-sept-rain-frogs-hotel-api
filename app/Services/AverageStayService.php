<?php

namespace App\Services;

use App\Models\Booking;
use App\Models\Room;
use Carbon\Carbon;

class AverageStayService
{
    public static function getAverageStay()
    {

        $roomsWithBookings = Room::with('booking')
            ->get()?->makeHidden(['rate', 'image', 'min_capacity', 'max_capacity', 'description', 'type_id', 'created_at',
                'updated_at', 'booking']);

        $total = 0;


        foreach ($roomsWithBookings as $roomWithBooking) {
            dd($roomWithBooking);

            $checkIn = Carbon::parse($roomWithBooking['start']);
            $checkOut = Carbon::parse($roomWithBooking['end']);

            $total += $checkIn->diffInDays($checkOut);
        }

        $stayCount = count($roomsWithBookings);
        $averageStay = $stayCount > 0 ? $total / $stayCount : 0;

        return round($averageStay, 2);
    }
}

//
//        foreach ($roomsWithBookings as $room) {
//            $bookingStartDate = strtotime($room->start);
//            $bookingEndDate = strtotime($room->end);
//
//            $bookingDuration = $bookingEndDate - $bookingStartDate;
//            $bookingDurationInDays = $bookingDuration / (60 * 60 * 24);
//            $total += $bookingDurationInDays;
//        }
//
//        $result = round($total / $bookingCount, 1);

