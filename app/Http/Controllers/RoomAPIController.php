<?php

namespace App\Http\Controllers;

use App\Models\Room;
use Illuminate\Http\Request;

class RoomAPIController extends Controller
{
   public function find(int $id): \Illuminate\Http\JsonResponse
   {
       $room = Room::with('type:id,name')->find($id)?->makeHidden(['type_id', 'created_at', 'updated_at']);
       if (!$room) {
           return response()->json([
               'message'=> "Room with id {$id} not found"
           ],404);
       } else {
           return response()->json([
               'message'=> 'Room successfully retrieved',
               'data'=> $room
           ]);
       }
   }
}
