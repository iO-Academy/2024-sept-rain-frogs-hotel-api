<?php

namespace Tests\Feature;

use App\Models\Room;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Testing\Fluent\AssertableJson;
use Tests\TestCase;

class RoomTest extends TestCase
{
    use DatabaseMigrations;

    public function test_getRoom_by_id_success(): void
    {
        Room::factory()->create();
        $response = $this->get('/api/rooms/1');
        $response->assertStatus(200)
            ->assertJson(function(AssertableJson $json) {
               $json->hasAll(['message', 'data'])
                ->has('data', function(AssertableJson $json) {
                    $json->hasAll(['id', 'name', 'rate', 'image',
                        'min_capacity', 'max_capacity', 'description',
                        'type_id', 'created_at', 'updated_at'])
                    ->whereAllType([
                        'id' => 'integer',
                        'name' => 'string',
                        'rate' => 'integer',
                        'image' => 'string',
                        'min_capacity' => 'integer',
                        'max_capacity' => 'integer',
                        'description' => 'string',
                        'type' => 'array',
                        'created_at' => 'string|null',
                        'updated_at' => 'string|null'
                    ]);
                });
            });
    }
    public function test_getRoom_by_id_failure(): void
    {
        $response = $this->get('/api/rooms/100');
        $response->assertStatus(404);
    }
}
