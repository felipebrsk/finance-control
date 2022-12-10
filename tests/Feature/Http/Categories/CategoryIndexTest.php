<?php

namespace Tests\Feature\Http\Categories;

use Tests\TestCase;
use Tests\Traits\{HasDummyCategory, HasDummySpace, HasDummyUser};

class CategoryIndexTest extends TestCase
{
    use HasDummyUser;
    use HasDummySpace;
    use HasDummyCategory;

    /**
     * The dummy user.
     * 
     * @var \App\Models\User
     */
    private $user;

    /**
     * The dummy space.
     * 
     * @var \App\Models\Space
     */
    private $space;

    /**
     * Setup new test environments.
     * 
     * @return void
     */
    public function setUp(): void
    {
        parent::setUp();

        $this->user = $this->actingAsDummyUser();
        $this->space = $this->createDummySpaceTo($this->user);
    }

    /**
     * Test if can get categories route.
     * 
     * @return void
     */
    public function test_if_can_get_categories_route(): void
    {
        $this->getJson(route('categories.index'))->assertOk();
    }

    /**
     * Test if can get correctly categories json count.
     * 
     * @return void
     */
    public function test_if_can_get_correctly_categories_json_count(): void
    {
        $this->getJson(route('categories.index'))->assertOk()->assertJsonCount(0, 'data');

        $this->createDummyCategoryTo($this->space);

        $this->getJson(route('categories.index'))->assertOk()->assertJsonCount(1, 'data');
    }

    /**
     * Test if can get correctly categories json structure.
     * 
     * @return void
     */
    public function test_if_can_get_correctly_categories_json_structure(): void
    {
        $this->createDummyCategoryTo($this->space);

        $this->getJson(route('categories.index'))->assertOk()->assertJsonStructure([
            'data' => [
                '*' => [
                    'id',
                    'name',
                    'slug',
                    'color',
                    'created_at',
                    'updated_at',
                    'space' => [
                        'id',
                        'name',
                        'slug',
                        'monthly_earning_recurrings',
                        'monthly_balance',
                        'monthly_spending_recurrings',
                        'monthly_recurrings_calculated',
                        'created_at',
                        'updated_at',
                        'currency' => [
                            'id',
                            'name',
                            'iso',
                            'symbol',
                            'created_at',
                            'updated_at',
                        ],
                    ],
                ],
            ],
        ]);
    }

    /**
     * Test if can't get another user categories on count.
     * 
     * @return void
     */
    public function test_if_cant_get_another_user_categories_on_count(): void
    {
        $this->getJson(route('categories.index'))->assertOk()->assertJsonCount(0, 'data');

        $this->createDummyCategories(3);

        $this->getJson(route('categories.index'))->assertOk()->assertJsonCount(0, 'data');
    }

    /**
     * Test if can get correctly json space.
     * 
     * @return void
     */
    public function test_if_can_get_correctly_json_space(): void
    {
        $category = $this->createDummyCategoryTo($this->space);

        $this->getJson(route('categories.index'))->assertOk()->assertJson([
            'data' => [
                [
                    'id' => $category->id,
                    'name' => $category->name,
                    'slug' => $category->slug,
                    'color' => $category->color,
                    'created_at' => $category->created_at->toIsoString(),
                    'updated_at' => $category->updated_at->toIsoString(),
                    'space' => [
                        'id' => $category->space->id,
                        'name' => $category->space->name,
                        'slug' => $category->space->slug,
                        'monthly_earning_recurrings' => formatCurrency($category->space->getMonthlyEarningRecurrings(), $category->space->currency->iso),
                        'monthly_balance' => formatCurrency($category->space->getMonthlyBalance(), $category->space->currency->iso),
                        'monthly_spending_recurrings' => formatCurrency($category->space->getMonthlySpendingRecurrings(), $category->space->currency->iso),
                        'monthly_recurrings_calculated' => formatCurrency($category->space->calculateMonthlyRecurrings(), $category->space->currency->iso),
                        'created_at' => $category->space->created_at->toIsoString(),
                        'updated_at' => $category->space->updated_at->toIsoString(),
                        'currency' => [
                            'id' => $category->space->currency->id,
                            'name' => $category->space->currency->name,
                            'iso' => $category->space->currency->iso,
                            'symbol' => $category->space->currency->symbol,
                            'created_at' => $category->space->currency->created_at->toIsoString(),
                            'updated_at' => $category->space->currency->updated_at->toIsoString(),
                        ],
                    ],
                ],
            ],
        ]);
    }
}
