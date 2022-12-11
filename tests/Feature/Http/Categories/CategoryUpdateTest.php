<?php

namespace Tests\Feature\Http\Categories;

use Tests\TestCase;
use Illuminate\Support\Str;
use Tests\Traits\{HasDummyCategory, HasDummySpace, HasDummyUser};

class CategoryUpdateTest extends TestCase
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
     * The dummy category.
     *
     * @var \App\Models\Category
     */
    private $category;

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
        $this->category = $this->createDummyCategoryTo($this->space);
    }

    /**
     * Get the valid category payload.
     *
     * @return array
     */
    protected function getValidCategoryPayload(): array
    {
        return [
            'name' => fake()->name(),
            'color' => fake()->colorName(),
        ];
    }

    /**
     * Test if can throw 404 if category doesn't exists.
     *
     * @return void
     */
    public function test_if_can_throw_not_found_if_category_doesnt_exists(): void
    {
        $this->putJson(route('categories.update', 999999999))->assertNotFound();
    }

    /**
     * Test if can't update a category that doesn't belongs to user space.
     *
     * @return void
     */
    public function test_if_cant_update_a_category_that_doesnt_belongs_to_user_space(): void
    {
        $this->putJson(route('categories.update', $this->createDummyCategory()->id))
            ->assertForbidden()
            ->assertSee('Esta categoria n\u00e3o pertence \u00e0 nenhum dos seus espa\u00e7os. Nenhuma opera\u00e7\u00e3o pode ser realizada.');
    }

    /**
     * Test if can update a category without payload.
     *
     * @return void
     */
    public function test_if_can_update_a_category_without_payload(): void
    {
        $this->putJson(route('categories.update', $this->category->id))->assertOk();
    }

    /**
     * Test if can update a category with correctly payload.
     *
     * @return void
     */
    public function test_if_can_update_a_category_with_correctly_payload(): void
    {
        $this->putJson(route('categories.update', $this->category->id), $this->getValidCategoryPayload())->assertOk();
    }

    /**
     * Test if can update a category in database.
     *
     * @return void
     */
    public function test_if_can_update_a_category_in_database(): void
    {
        $this->putJson(route('categories.update', $this->category->id), $data = $this->getValidCategoryPayload())->assertOk();

        $this->assertDatabaseHas('categories', [
            'name' => $data['name'],
            'slug' => Str::slug($data['name']),
            'color' => $data['color'],
        ]);
    }

    /**
     * Test if can return correctly category json structure.
     *
     * @return void
     */
    public function test_if_can_return_correctly_category_json_structure(): void
    {
        $this->putJson(route('categories.update', $this->category->id), $data = $this->getValidCategoryPayload())->assertOk()->assertJsonStructure([
            'data' => [
                'id',
                'name',
                'slug',
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
        ]);
    }

    /**
     * Test if can return correctly category json.
     *
     * @return void
     */
    public function test_if_can_return_correctly_category_json(): void
    {
        $this->putJson(route('categories.update', $this->category->id), $data = $this->getValidCategoryPayload())->assertOk()->assertJson([
            'data' => [
                'id' => $this->category->id,
                'name' => $data['name'],
                'slug' => Str::slug($data['name']),
                'created_at' => $this->category->created_at->toIsoString(),
                'updated_at' => $this->category->updated_at->toIsoString(),
                'space' => [
                    'id' => $this->category->space->id,
                    'name' => $this->category->space->name,
                    'slug' => $this->category->space->slug,
                    'monthly_earning_recurrings' => formatCurrency($this->category->space->getMonthlyEarningRecurrings(), $this->category->space->currency->iso),
                    'monthly_balance' => formatCurrency($this->category->space->getMonthlyBalance(), $this->category->space->currency->iso),
                    'monthly_spending_recurrings' => formatCurrency($this->category->space->getMonthlySpendingRecurrings(), $this->category->space->currency->iso),
                    'monthly_recurrings_calculated' => formatCurrency($this->category->space->calculateMonthlyRecurrings(), $this->category->space->currency->iso),
                    'created_at' => $this->category->space->created_at->toIsoString(),
                    'updated_at' => $this->category->space->updated_at->toIsoString(),
                    'currency' => [
                        'id' => $this->category->space->currency->id,
                        'name' => $this->category->space->currency->name,
                        'iso' => $this->category->space->currency->iso,
                        'symbol' => $this->category->space->currency->symbol,
                        'created_at' => $this->category->space->currency->created_at->toIsoString(),
                        'updated_at' => $this->category->space->currency->updated_at->toIsoString(),
                    ],
                ],
            ],
        ]);
    }
}
