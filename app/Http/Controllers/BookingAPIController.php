<?php

namespace App\Http\Controllers;

use App\Models\Booking;
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
}
