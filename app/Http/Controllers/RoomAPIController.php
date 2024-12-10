<?php

namespace App\Http\Controllers;

use App\Models\Room;
use Illuminate\Http\Request;

class RoomAPIController extends Controller
{
    public function index()
    {
        $rooms = Room::with('type:id,name')->get()?->makeHidden(['type_id', 'created_at', 'updated_at', 'description', 'rate']);
        // This call allows us to link the Rooms to the Types tables
        // through a get method

        return response()->json([
            'message' => 'Rooms successfully retrieved',
            'data' => $rooms
        ]);
    }
}
