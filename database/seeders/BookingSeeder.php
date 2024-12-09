<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class BookingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = \Faker\Factory::create();
        for ($i = 1; $i < 11; $i++) {
            $start_date = Carbon::now()->addDays(rand(1,365))->format('Y-m-d');
            DB::table('bookings')->insert([
                'customer' => $faker->name,
                'start' => $start_date,
                'end' => Carbon::createFromFormat('Y-m-d',$start_date)
                ->addDays(rand(1,30)),
                'room'=> rand(1,10)
            ]);
        }
    }
}
