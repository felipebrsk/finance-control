<?php

namespace Database\Factories;

use App\Models\{Category, Currency, Space};
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Recurring>
 */
class RecurringFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'description' => fake()->text(),
            'amount' => fake()->numberBetween(100, 29900),
            'type' => fake()->randomElement(['spending', 'earning']),
            'interval' => fake()->randomElement(['yearly', 'monthly', 'biweekly', 'weekly', 'daily']),
            'day' => fake()->dayOfMonth(),
            'start_date' => fake()->dayOfMonth(),
            'category_id' => Category::factory()->create(),
            'space_id' => Space::factory()->create(),
            'currency_id' => Currency::firstOrCreate([
                'iso' => 'BRL',
            ], [
                'name' => 'Real brasileiro',
                'symbol' => 'R$'
            ]),
        ];
    }
}
