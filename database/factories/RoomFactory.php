<?php

namespace Database\Factories;

use App\Models\Type;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Room>
 */
class RoomFactory extends Factory
{
    use HasFactory;
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */

    public function definition(): array
    {
        return [
            'name' =>$this->faker->sentence(2),
            'description' => $this->faker->paragraph(2),
            'image' => $this->faker->imageUrl($width = 640, $height = 480),
            'min_capacity' => rand(1,2),
            'max_capacity' => rand(2,6),
            'rate'=>rand(50,300),
            'type_id'=>Type::factory(),
        ];
    }
}
