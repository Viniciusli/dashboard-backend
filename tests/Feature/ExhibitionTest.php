<?php

namespace Tests\Feature;

use App\Models\Author;
use App\Models\Exhibition;
use App\Models\Museum;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ExhibitionTest extends TestCase
{
    public function test_exibitions_can_be_listed(): void
    {
        Exhibition::factory()->count(5)->create()
            ->each(function ($exhibition) {
                $exhibition->authors()->saveMany(Author::factory()->count(2)->make());
                $exhibition->audios()->saveMany([
                    ['file' => 'Audio File', 'file_original' => 'Audio File Original', 'lang' => 'pt-br'],
                    ['file' => 'Audio File 2', 'file_original' => 'Audio File Original 2', 'lang' => 'en'],
                    ['file' => 'Audio File 3', 'file_original' => 'Audio File Original 3', 'lang' => 'es'],
                ]);
                $exhibition->videos()->saveMany([
                    ['file' => 'Video File', 'file_original' => 'Video File Original', 'lang' => 'pt-br'],
                    ['file' => 'Video File 2', 'file_original' => 'Video File Original 2', 'lang' => 'en'],
                    ['file' => 'Video File 3', 'file_original' => 'Video File Original 3', 'lang' => 'es'],
                ]);
            });

        $response = $this->get('/api/exhibitions');

        $response->assertStatus(200);
        $response->assertJsonCount(5);
        $response->assertJsonHas('0.authors');
        $response->assertJsonHas('0.audios');
        $response->assertJsonHas('0.videos');
    }

    public function test_exhibition_can_be_created(): void
    {
        $museum = Museum::factory()->create();
        Author::factory()->create()->count(5);

        $response = $this->postJson('/api/exhibitions', [
            'museum_id' => $museum->id,
            'name' => 'Exhibition Name',
            'authors' => [
                [1, 2],
            ],
            'audios' => [
                [
                    'file' => 'Audio File',
                    'file_original' => 'Audio File Original',
                    'lang' => 'pt-br',
                ],
                [
                    'file' => 'Audio File 2',
                    'file_original' => 'Audio File Original 2',
                    'lang' => 'en',
                ],
                [
                    'file' => 'Audio File 3',
                    'file_original' => 'Audio File Original 3',
                    'lang' => 'es',
                ],
            ],
            'videos' => [
                [
                    'file' => 'Video File',
                    'file_original' => 'Video File Original',
                    'lang' => 'pt-br',
                ],
                [
                    'file' => 'Video File 2',
                    'file_original' => 'Video File Original 2',
                    'lang' => 'en',
                ],
                [
                    'file' => 'Video File 3',
                    'file_original' => 'Video File Original 3',
                    'lang' => 'es',
                ],
            ],
        ]);

        $response->assertStatus(201);
    }

    public function test_exhibition_name_can_be_updated()
    {
        $exhibition = Exhibition::factory()->create();

        $response = $this->putJson("/api/exhibitions/{$exhibition->id}", [
            'name' => 'Exhibition Name updated',
        ]);

        $this->assertDatabaseHas('exhibitions', [
            'name' => 'Exhibition Name updated',
        ]);
        $response->assertStatus(200);
    }

    public function test_exhibition_authors_can_be_updated()
    {
        $exhibition = Exhibition::factory()->count(5)->create()
            ->each(function ($exhibition) {
                $exhibition->authors()->saveMany(Author::factory()->count(2)->make());
            });

        $response = $this->putJson("/api/exhibitions/{$exhibition->id}/authors/1", [
            'name' => 'Author Name updated',
            'bio' => 'Author Bio updated',
            'photo' => 'Author Photo updated',
        ]);

        $this->assertDatabaseHas('authors', [
            'name' => 'Author Name updated',
            'bio' => 'Author Bio updated',
            'photo' => 'Author Photo updated',
        ]);
        $response->assertStatus(200);
    }

    public function test_exhibition_authors_can_be_removed()
    {
        $exhibition = Exhibition::factory()->create()
            ->each(function ($exhibition) {
                $exhibition->authors()->saveMany(Author::factory()->count(2)->make());
            });

        $response = $this->delete("/api/exhibitions/{$exhibition->id}/authors/1");

        $this->assertDatabaseMissing('exhibitions_authors', [
            'exhibition_id' => $exhibition->id,
            'author_id' => 1,
        ]);
        $response->assertStatus(200);
    }

    public function test_exhibition_videos_can_be_updated()
    {
        $exhibition = Exhibition::factory()->create()
            ->each(function ($exhibition) {
                $exhibition->videos()->saveMany([
                    ['file' => 'Video File', 'file_original' => 'Video File Original', 'lang' => 'pt-br'],
                    ['file' => 'Video File 2', 'file_original' => 'Video File Original 2', 'lang' => 'en'],
                    ['file' => 'Video File 3', 'file_original' => 'Video File Original 3', 'lang' => 'es'],
                ]);
            });

        $response = $this->putJson("/api/exhibitions/{$exhibition->id}/videos/1", [
            'file' => 'Video File updated',
            'file_original' => 'Video File Original updated',
            'lang' => 'pt-br',
        ]);

        $this->assertDatabaseHas('videos', [
            'file' => 'Video File updated',
            'file_original' => 'Video File Original updated',
            'lang' => 'pt-br',
        ]);
        $response->assertStatus(200);
    }

    public function test_exhibition_videos_can_be_deleted()
    {
        $exhibition = Exhibition::factory()->create()
            ->each(function ($exhibition) {
                $exhibition->videos()->saveMany([
                    ['file' => 'Video File', 'file_original' => 'Video File Original', 'lang' => 'pt-br'],
                    ['file' => 'Video File 2', 'file_original' => 'Video File Original 2', 'lang' => 'en'],
                    ['file' => 'Video File 3', 'file_original' => 'Video File Original 3', 'lang' => 'es'],
                ]);
            });

        $response = $this->delete("/api/exhibitions/{$exhibition->id}/videos/1");

        $this->assertDatabaseMissing('videos', [
            'id' => 1,
        ]);
        $response->assertStatus(200);
    }

    public function test_exhibition_audios_can_be_updated()
    {
        $exhibition = Exhibition::factory()->create()
            ->each(function ($exhibition) {
                $exhibition->audios()->saveMany([
                    ['file' => 'Audio File', 'file_original' => 'Audio File Original', 'lang' => 'pt-br'],
                    ['file' => 'Audio File 2', 'file_original' => 'Audio File Original 2', 'lang' => 'en'],
                    ['file' => 'Audio File 3', 'file_original' => 'Audio File Original 3', 'lang' => 'es'],
                ]);
            });

        $response = $this->putJson("/api/exhibitions/{$exhibition->id}/audios/1", [
            'file' => 'Audio File updated',
            'file_original' => 'Audio File Original updated',
            'lang' => 'pt-br',
        ]);

        $this->assertDatabaseHas('audios', [
            'file' => 'Audio File updated',
            'file_original' => 'Audio File Original updated',
            'lang' => 'pt-br',
        ]);
        $response->assertStatus(200);
    }

    public function test_exhibition_audios_can_be_deleted()
    {
        $exhibition = Exhibition::factory()->create()
            ->each(function ($exhibition) {
                $exhibition->audios()->saveMany([
                    ['file' => 'Audio File', 'file_original' => 'Audio File Original', 'lang' => 'pt-br'],
                    ['file' => 'Audio File 2', 'file_original' => 'Audio File Original 2', 'lang' => 'en'],
                    ['file' => 'Audio File 3', 'file_original' => 'Audio File Original 3', 'lang' => 'es'],
                ]);
            });

        $response = $this->delete("/api/exhibitions/{$exhibition->id}/audios/1");

        $this->assertDatabaseMissing('audios', [
            'id' => 1,
        ]);
        $response->assertStatus(200);
    }

    public function test_exhibition_can_be_deleted(): void
    {
        $exhibition = Exhibition::factory()->create();

        $response = $this->delete("/api/exhibitions/{$exhibition->id}");

        $this->assertDatabaseMissing('exhibitions', [
            'id' => $exhibition->id,
        ]);
        $this->assertDatabaseMissing('exhibitions_authors', [
            'exhibition_id' => $exhibition->id,
        ]);
        $this->assertDatabaseMissing('audios', [
            'exhibition_id' => $exhibition->id,
        ]);
        $this->assertDatabaseMissing('videos', [
            'exhibition_id' => $exhibition->id,
        ]);
        $response->assertStatus(200);
    }
}
