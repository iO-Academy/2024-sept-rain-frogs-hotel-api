<?php

namespace Tests\Feature;

use App\Models\Room;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class RoomTest extends TestCase
{
    use DatabaseMigrations;

    public function test_getRoom_by_id_success(): void
    {
        Room::factory()->create();
        $response = $this->get('/api/rooms/1');
        $response->assertStatus(200);
    }
    public function test_getRoom_by_id_failure(): void
    {
        $response = $this->get('/api/rooms/100');
        $response->assertStatus(404);
    }
}
