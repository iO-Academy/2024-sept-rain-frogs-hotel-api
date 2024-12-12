<?php

namespace App\Services;

use App\Models\Booking;
use App\Models\Room;

class BookingService
{
    public static function dateConflict($room, $startDate, $endDate)
    {
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

        return $bookingConflict;
    }

    public static function checkCapacity($room, $request): bool
    {
//        return $room->min_capacity > $request->guests OR  $room->max_capacity < $request->guests;

        if ($room->min_capacity > $request->guests OR $room->max_capacity < $request->guests) {
            return true;
        } else {
            return false;
        }
    }
}

