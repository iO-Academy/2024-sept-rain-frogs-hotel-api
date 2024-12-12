<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use Illuminate\Http\Request;

class BookingAPIController extends Controller
{
    public function index(Request $request)
    {
        $request->validate([
            'room_id' => 'nullable|exists:rooms,id',
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
            ->get()?->makeHidden(['updated_at', 'room_id'])
            ->sortBy('start');

        return response()->json([
            'message' => 'Booking retrieved successfully.',
            'success' => true,
            'data' => $currentAndFutureBookings
        ]);
    }
}
