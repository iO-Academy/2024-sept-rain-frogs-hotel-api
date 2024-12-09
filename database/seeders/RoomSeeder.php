<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RoomSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = \Faker\Factory::create();
        for ($i = 0; $i < 30; $i++) {
            DB::table('rooms')->insert([
                'name' => $faker->sentence(2),
                'description' => $faker->paragraph(2),
                'image' => $faker->imageUrl($width = 640, $height = 480),
                'min_capacity' => rand(1,2),
                'max_capacity' => rand(2,6),
                'rate'=>rand(50,300),
                'type'=>rand(1,5),
            ]);
        }
    }
}
