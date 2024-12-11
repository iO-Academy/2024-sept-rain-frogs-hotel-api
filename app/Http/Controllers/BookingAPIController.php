<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use Illuminate\Http\Request;

class BookingAPIController extends Controller
{
    public function index(Request $request)
    {
        $query = Booking::query();
        if ($request->has('room_id')) {
            $query->where('room_id', $request->room_id);
        }

        $bookings = $query->get();

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


}
