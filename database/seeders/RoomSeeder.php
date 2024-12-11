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
                'image' => $faker->imageUrl($width = 400, $height = 400),
                'min_capacity' => rand(1,2),
                'max_capacity' => rand(2,6),
                'rate' => $faker->randomFloat(2, 50,300),
                'type_id' => rand(1,5),
            ]);
        }
    }
}
