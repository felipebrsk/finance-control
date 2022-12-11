<?php

namespace Tests\Feature\Http\Categories;

use Tests\TestCase;
use Illuminate\Support\Str;
use Tests\Traits\{HasDummySpace, HasDummyUser};

class CategoryStoreTest extends TestCase
{
    use HasDummyUser;
    use HasDummySpace;

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
     * Get the valid category payload.
     *
     * @return array
     */
    protected function getValidCategoryPayload(): array
    {
        return [
            'name' => fake()->name(),
            'color' => fake()->colorName(),
            'space_id' => $this->space->id,
        ];
    }

    /**
     * Test if can't create a category without payload.
     *
     * @return void
     */
    public function test_if_cant_create_a_category_without_payload(): void
    {
        $this->postJson(route('categories.store'))
            ->assertUnprocessable()
            ->assertSee('The name field is required. (and 1 more error)');
    }

    /**
     * Test if can't create a category with space that doesn't belongs to user.
     *
     * @return void
     */
    public function test_if_cant_create_a_category_with_space_that_doesnt_belongs_to_user(): void
    {
        $this->postJson(route('categories.store'), [
            'name' => fake()->name(),
            'color' => fake()->colorName(),
            'space_id' => $this->createDummySpace()->id,
        ])->assertForbidden()
            ->assertSee('O espa\u00e7o n\u00e3o pertence ao seu us\u00e1rio. Nenhuma opera\u00e7\u00e3o pode ser realizada.');
    }

    /**
     * Test if can create a category with correctly payload.
     *
     * @return void
     */
    public function test_if_can_create_a_category_with_correctly_payload(): void
    {
        $this->postJson(route('categories.store'), $this->getValidCategoryPayload())->assertCreated();
    }

    /**
     * Test if can save the category in database.
     *
     * @return void
     */
    public function test_if_can_save_the_category_in_database(): void
    {
        $this->postJson(route('categories.store'), $data = $this->getValidCategoryPayload())->assertCreated();

        $this->assertDatabaseHas('categories', [
            'name' => $data['name'],
            'slug' => Str::slug($data['name']),
            'color' => $data['color'],
            'space_id' => $data['space_id'],
        ]);
    }

    /**
     * Test if can return the correctly category json structure.
     *
     * @return void
     */
    public function test_if_can_return_the_correctly_json_structure(): void
    {
        $this->postJson(route('categories.store'), $this->getValidCategoryPayload())->assertCreated()->assertJsonStructure([
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
     * Test if can return the correctly category json.
     *
     * @return void
     */
    public function test_if_can_return_the_correctly_category_json(): void
    {
        $this->postJson(route('categories.store'), $data = $this->getValidCategoryPayload())->assertCreated()->assertJson([
            'data' => [
                'name' => $data['name'],
                'slug' => Str::slug($data['name']),
                'color' => $data['color'],
                'space' => [
                    'id' => $data['space_id'],
                ],
            ],
        ]);
    }
}
