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
            $json->hasAll(['message', 'success', 'data']);
        });
    }
}
