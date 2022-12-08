<?php

namespace Tests\Feature\Http\Spendings;

use Tests\TestCase;
use Tests\Traits\{HasDummyCategory, HasDummySpace, HasDummySpending, HasDummyUser};

class SpendingStoreTest extends TestCase
{
    use HasDummyUser;
    use HasDummySpace;
    use HasDummyCategory;
    use HasDummySpending;

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
     * Get valid spending payload.
     * 
     * @return array
     */
    protected function getValidSpendingPayload(): array
    {
        return [
            'category_id' => $this->createDummyCategory([
                'space_id' => $this->space->id,
            ])->id,
            'description' => fake()->text(),
            'amount' => fake()->numberBetween(100, 29900),
            'when' => fake()->date(),
            'space_id' => $this->space->id,
        ];
    }

    /**
     * Test if can't create a spending without payload.
     * 
     * @return void
     */
    public function test_if_cant_create_a_spending_without_payload(): void
    {
        $this->postJson(route('spendings.store'))
            ->assertUnprocessable()
            ->assertSee('The description field is required. (and 3 more errors)');
    }

    /**
     * Test if can't create a spending with category that doesn't belongs to user.
     * 
     * @return void
     */
    public function test_if_cant_create_a_spending_with_category_that_doesnt_belongs_to_user(): void
    {
        $this->postJson(route('spendings.store'), [
            'category_id' => $this->createDummyCategory()->id,
            'description' => fake()->text(),
            'amount' => fake()->numberBetween(100, 29900),
            'when' => fake()->date(),
            'space_id' => $this->space->id,
        ])->assertForbidden()
            ->assertSee('Esta categoria n\u00e3o pertence \u00e0 nenhum dos seus espa\u00e7os. Nenhuma opera\u00e7\u00e3o pode ser realizada.');
    }

    /**
     * Test if can't create a spending with space that doesn't belongs to user.
     * 
     * @return void
     */
    public function test_if_cant_create_a_spending_with_space_that_doesnt_belongs_to_user(): void
    {
        $this->postJson(route('spendings.store'), [
            'category_id' => $this->createDummyCategory([
                'space_id' => $this->space->id,
            ])->id,
            'description' => fake()->text(),
            'amount' => fake()->numberBetween(100, 29900),
            'when' => fake()->date(),
            'space_id' => $this->createDummySpace()->id,
        ])->assertForbidden()
            ->assertSee('O espa\u00e7o n\u00e3o pertence ao seu us\u00e1rio. Nenhuma opera\u00e7\u00e3o pode ser realizada.');
    }

    /**
     * Test if can create a spending with correctly payload.
     * 
     * @return void
     */
    public function test_if_can_create_a_spending_with_correctly_payload(): void
    {
        $this->postJson(route('spendings.store'), $this->getValidSpendingPayload())->assertCreated();
    }

    /**
     * Test if can create correctly spending on database.
     * 
     * @return void
     */
    public function test_if_can_create_correctly_spending_on_database(): void
    {
        $this->postJson(route('spendings.store'), $data = $this->getValidSpendingPayload())->assertCreated();

        $this->assertDatabaseCount('spendings', 1)
            ->assertDatabaseHas('spendings', [
                'description' => $data['description'],
                'category_id' => $data['category_id'],
                'amount' => $data['amount'],
                'when' => $data['when'],
                'space_id' => $data['space_id'],
            ]);
    }

    /**
     * Test if can return correctly json spending structure.
     * 
     * @return void
     */
    public function test_if_can_return_correctly_json_spending_structure(): void
    {
        $this->postJson(route('spendings.store'), $this->getValidSpendingPayload())->assertCreated()->assertJsonStructure([
            'data' => [
                'id',
                'description',
                'amount',
                'when',
                'created_at',
                'updated_at',
                'space' => [
                    'id',
                    'name',
                    'slug',
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
     * Test if can return correctly json spending on creation.
     * 
     * @return void
     */
    public function test_if_can_return_correctly_json_spending_on_creation(): void
    {
        $this->postJson(route('spendings.store'), $data = $this->getValidSpendingPayload())->assertCreated()->assertJson([
            'data' => [
                'description' => $data['description'],
                'amount' => formatCurrency($data['amount'], $this->space->currency->iso),
                'when' => $data['when'],
                'space' => [
                    'id' => $data['space_id'],
                    'name' => $this->space->name,
                    'slug' => $this->space->slug,
                    'created_at' => $this->space->created_at->toIsoString(),
                    'updated_at' => $this->space->updated_at->toIsoString(),
                    'currency' => [
                        'id' => $this->space->currency->id,
                        'name' => $this->space->currency->name,
                        'iso' => $this->space->currency->iso,
                        'symbol' => $this->space->currency->symbol,
                        'created_at' => $this->space->currency->created_at->toIsoString(),
                        'updated_at' => $this->space->currency->updated_at->toIsoString(),
                    ],
                ],
            ],
        ]);
    }
}
