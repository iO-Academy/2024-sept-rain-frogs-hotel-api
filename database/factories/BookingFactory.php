<?php

namespace Database\Factories;

use App\Models\Room;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Carbon;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Booking>
 */
class BookingFactory extends Factory
{
    use HasFactory;
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $start_date = Carbon::now()->addDays(rand(1,365))->format('Y-m-d');
        $end_date = Carbon::createFromFormat('Y-m-d',$start_date)
            ->addDays(rand(1,30));
        return [
            'id' => rand(1,10),
            'customer' => $this->faker->name,
            'start' => $start_date,
            'end' => $end_date,
            'created_at' => Carbon::now(),
            'room_id'=> Room::factory()
            ];
    }
}
