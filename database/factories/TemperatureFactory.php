<?php

namespace Database\Factories;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Temperature>
 */
class TemperatureFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'bdcom_id' => random_int(1, 14),
            'temperature' => random_int(30, 100),
            'created_at' => Carbon::today()->subDays(rand(1, 365)),
            'updated_at' => Carbon::today()->subDays(rand(1, 365)),
        ];
    }
}
