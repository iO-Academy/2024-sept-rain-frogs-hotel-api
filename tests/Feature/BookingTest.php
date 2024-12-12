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
                ->has('data', 1, function (AssertableJson $data) {
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

    public function test_bookRoom_success(): void
    {
        Room::factory()->create();

        $testData = [
            "room_id" => 1,
            "customer" => "Test customer",
            "guests" => 2,
            "start" => "2024-12-15",
            "end" => "2024-12-25"
        ];

        $response = $this->postJson('/api/bookings', $testData);

        $response->assertStatus(201)
        ->assertJson(function (AssertableJson $json){
            $json->hasAll(['message']);
        });

        $this->assertDatabaseHas('bookings', [
            "room_id" => 1,
            "customer" => "Test customer",
            "start" => "2024-12-15",
            "end" => "2024-12-25"
        ]);
    }

    public function test_bookRoom_failure_dueTo_requiredFieldsMissing()
    {
        $testData = [];

        $response = $this->postJson('/api/bookings', $testData);

        $response->assertStatus(422)
            ->assertInvalid(['room_id', 'customer', 'guests', 'start', 'end']);
    }

    public function test_bookRoom_failure_dueTo_incorrectRoomCapacity()
    {
        Room::factory()->create();

        $testData = [
            "room_id" => 1,
            "customer" => "Test customer",
            "guests" => 500,
            "start" => "2024-12-15",
            "end" => "2024-12-25"
        ];

        $response = $this->post('/api/bookings', $testData);

        $response->assertStatus(400)
            ->assertJson(function (AssertableJson $json){
                $json->hasAll(['message']);
            });
    }

    public function test_bookRoom_failure_dueTo_endDateBeforeStartDate()
    {
        Booking::factory()->create();
        Room::factory()->create();

        $testData = [
            "room_id" => 1,
            "customer" => "Test customer",
            "guests" => 2,
            "start" => "2026-12-25",
            "end" => "2026-12-01"
        ];

        $response = $this->postJson('/api/bookings', $testData);

        $response->assertStatus(422)
            ->assertInvalid(['end']);
    }

    public function test_bookRoom_failure_dueTo_roomUnavailable_OnThoseDates()
    {
        Booking::factory()->create();
        Room::factory()->create();

        $testData = [
            "room_id" => 1,
            "customer" => "Test customer",
            "guests" => 2,
            'start' => "2025-12-11",
            'end' => "2025-12-31",
        ];

        $response = $this->postJson('/api/bookings', $testData);

        $response->assertStatus(400)
            ->assertJson(function (AssertableJson $json){
                $json->hasAll(['message']);
            });
    }

    public function test_getBookingsById_success(): void
    {
        Booking::factory()->count(2)->create();

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
