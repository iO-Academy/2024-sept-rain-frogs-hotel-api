<?php

namespace App\Http\Controllers;

use App\Models\Room;
use Illuminate\Http\Request;

class RoomAPIController extends Controller
{
   public function find(int $id): \Illuminate\Http\JsonResponse
   {
       $room = Room::find($id);
       if($room){
           return response()->json([
               'message'=> 'Room successfully retrieved',
               'data'=> [
                   'id' => $room->id,
                   'name' => $room->name,
                   'rate' => $room->rate,
                   'image' => $room->image,
                   'min_capacity' => $room->min_capacity,
                   'max_capacity' => $room->max_capacity,
                   'description' => $room->description,
                   'type' => [
                       'id' => $room->type->id,
                       'name' => $room->type->name,
                    ]
               ]
           ]);
       } else {
           return response()->json([
               'message'=> "Room with id {$id} not found"
           ],404);
       }
   }
}
