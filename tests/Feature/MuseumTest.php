<?php

namespace Tests\Feature;

use App\Models\Museum;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class MuseumTest extends TestCase
{
    use RefreshDatabase;

    protected User $user;

    public function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create();
    }

    public function test_museum_list(): void
    {
        Museum::factory()->count(5)->create();

        $response = $this->actingAs($this->user)
            ->getJson('/api/museums');

        $response->assertStatus(200);
        $response->assertJsonCount(5, 'data');
    }

    public function test_museum_can_be_created(): void
    {
        $response = $this->actingAs($this->user)
            ->postJson('/api/museums', [
                'name' => 'Museum of Modern Art',
                'address' => 'New York',
            ]);

        $response->assertStatus(201);
    }

    public function test_museum_can_be_updated(): void
    {
        $museum = Museum::factory()->create();

        $response = $this->actingAs($this->user)
            ->putJson("/api/museums/{$museum->id}", [
                'name' => 'Museum of Modern Art',
                'address' => 'New York',
            ]);

        $this->assertDatabaseHas('museums', [
            'name' => 'Museum of Modern Art',
            'address' => 'New York',
        ]);
        $response->assertStatus(200);
    }

    public function test_museum_can_be_deleted(): void
    {
        $museum = Museum::factory()->create();

        $response = $this->actingAs($this->user)
            ->deleteJson("/api/museums/{$museum->id}");

        $this->assertDatabaseMissing('museums', [
            'id' => $museum->id,
        ]);
        $response->assertStatus(200);
    }
}
