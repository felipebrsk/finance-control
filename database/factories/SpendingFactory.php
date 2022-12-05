<?php

namespace Database\Factories;

use Illuminate\Support\Carbon;
use App\Models\{Category, Recurring, Space};
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Spending>
 */
class SpendingFactory extends Factory
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
            'when' => Carbon::today()->toDateString(),
            'category_id' => Category::factory()->create(),
            'space_id' => Space::factory()->create(),
            'recurring_id' => Recurring::factory()->create(),
        ];
    }
}
