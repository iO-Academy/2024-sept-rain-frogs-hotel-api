<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use Illuminate\Http\Request;

class BookingAPIController extends Controller
{
    public function index(Request $request)
    {
        $request->validate([
            'room_id' => 'nullable|exists:rooms,id'
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
            ->orderBy('start')
            ->get()?->makeHidden(['updated_at', 'room_id']);

        return response()->json([
            'message' => 'Booking retrieved successfully.',
            'success' => true,
            'data' => $currentAndFutureBookings
        ]);
    }

    public function delete(int $id)
    {
        $booking = Booking::find($id);

        if(!$booking){
            return response()->json([
                "message" => "Unable to cancel booking, booking $id not found"
            ],404);
        }

        $booking->delete();
        return response()->json([
            "message"=>"Booking $id cancelled"
        ]);
    }
}
