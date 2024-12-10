<?php

namespace Tests\Feature;

use App\Models\Booking;
use App\Models\Room;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class BookingTest extends TestCase
{
    use DatabaseMigrations;

    // Test booking success
    // Test dates work correctly
    // Test room capacity
    // Test validation?

    public function test_bookRoom_success(): void
    {
        Booking::factory()->create();
        Room::factory()->create();

        $testData = [
            "room_id" => 1,
            "customer" => "Test customer",
            "guests" => 3,
            "start" => "2024-12-15",
            "end" => "2024-12-25"
        ];

        $response = $this->post('/api/bookings', $testData);

        $response->assertStatus(201);
    }
}
