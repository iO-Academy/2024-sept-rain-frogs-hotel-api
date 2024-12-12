<?php

namespace Tests\Feature;

use App\Models\Booking;
use App\Models\Room;
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
            }
            );
        });
    }
    public function test_reportDataSuccess(): void
    {
        Room::factory()->create();
        Booking::factory()->create();
        Booking::factory()->create();

        $response = $this->getJson('/api/bookings/report');
        $response->assertStatus(200)
            ->assertJson(function (AssertableJson $json) {
                $json->hasAll(['message', 'data'])
                    ->has('data', 3 , function (AssertableJson $data) {
                        $data->hasAll(['id', 'name', 'booking_count', 'average_booking_duration'])
                        ->whereAllType([
                            'id' => 'integer',
                            'name' => 'string',
                            'booking_count' => 'integer',
                            'average_booking_duration' => 'integer'
                        ]);
                    });
            });
    }
}
