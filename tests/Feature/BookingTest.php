<?php

namespace Tests\Feature;

use App\Models\Booking;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Testing\Fluent\AssertableJson;
use Tests\TestCase;

class BookingTest extends TestCase
{
    use DatabaseMigrations;

    public function test_getAllBookings_success(): void
    {
        Booking::factory()->create();

        $response = $this->getJson('/api/bookings');

        $response->assertStatus(200)
        ->assertJson(function (AssertableJson $json) {
            $json->hasAll(['message', 'success', 'data'])
            ->has('data', 1 , function (AssertableJson $data) {
                $data->hasAll(['id', 'customer', 'start', 'end', 'created_at', 'room'])
                    ->whereAllType([
                        'id' => 'integer',
                        'customer' => 'string',
                        'start' => 'string',
                        'end' => 'string',
                        'created_at' => 'string|null',
                        'room' => 'array'
                    ]);
            });
        });
    }

    public function test_getBookingsById_success(): void
    {
        Booking::factory()->create();

        $response = $this->getJson('/api/bookings?room_id=1');

        $response->assertStatus(200)
        ->assertJson(function (AssertableJson $json) {
           $json->hasAll(['message', 'success', 'data'])
            ->has('data', 1 , function (AssertableJson $data) {
                $data->hasAll(['id', 'customer', 'start', 'end', 'created_at', 'room'])
                    ->whereAllType([
                        'id' => 'integer',
                        'customer' => 'string',
                        'start' => 'string',
                        'end' => 'string',
                        'created_at' => 'string|null',
                        'room' => 'array'
                    ]);
            });
        });
    }

   public function test_getBookingsById_failure_idNotFound(): void
   {
       $response = $this->getJson('/api/bookings?room_id=1');
       $response->assertStatus(422)
           ->assertInvalid('room_id');
   }
}
