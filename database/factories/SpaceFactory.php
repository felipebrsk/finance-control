<?php

namespace Database\Factories;

use App\Models\{Currency, User};
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Space>
 */
class SpaceFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'name' => fake()->country(),
            'user_id' => User::factory()->create(),
            'currency_id' => Currency::firstOrCreate([
                'iso' => 'BRL',
            ], [
                'name' => 'Real brasileiro',
                'symbol' => 'R$'
            ]),
        ];
    }
}
