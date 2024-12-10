<?php

namespace App\Http\Controllers;

use App\Models\Room;
use Illuminate\Http\Request;

class RoomAPIController extends Controller
{
   public function find(int $id): \Illuminate\Http\JsonResponse
   {
       $rooms = Room::with('type')->get();
       $room = $rooms->firstWhere('id', $id);
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
