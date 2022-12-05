<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\{Category, Space, Earning, Recurring, Spending};

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Activity>
 */
class ActivityFactory extends Factory
{
    /**
     * The allowed activitables.
     * 
     * @var array
     */
    private const ALLOWED_ACTIVITABLES = [
        Spending::class,
        Earning::class,
        Category::class,
        Recurring::class,
    ];

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'action' => fake()->randomElement(['transaction.created', 'transaction.deleted', 'category.created', 'category.deleted']),
            'activitable_type' => $activitable = fake()->randomElement(self::ALLOWED_ACTIVITABLES),
            'activitable_id' => $activitable::factory()->create(),
            'space_id' => Space::factory()->create(),
        ];
    }
}
