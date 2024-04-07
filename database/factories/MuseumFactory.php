<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Museum>
 */
class MuseumFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => 'Museum of Modern Art',
            'images' => '["museum-of-modern-art.jpg"]',
            'address' => '11 W 53rd St, New York, NY 10019, United States',
            'latitude' => 40.761433,
            'longitude' => -73.977622,
        ];
    }
}
