<?php

namespace Tests\Feature\Http\Spendings;

use Tests\TestCase;
use Tests\Traits\{HasDummyCategory, HasDummySpace, HasDummySpending, HasDummyUser};

class SpendingUpdateTest extends TestCase
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
     * The dummy spending.
     * 
     * @var \App\Models\Spending
     */
    private $spending;

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
        $this->spending = $this->createDummySpendingTo($this->space);
    }

    /**
     * Get valid spending payload.
     * 
     * @return array
     */
    protected function getValidSpendingPayload(): array
    {
        return [
            'category_id' => $this->createDummyCategoryTo($this->space)->id,
            'description' => fake()->text(),
            'amount' => fake()->numberBetween(100, 29900),
            'when' => fake()->date(),
            'space_id' => $this->createDummySpaceTo($this->user)->id,
        ];
    }

    /**
     * Test if can update a spending without payload.
     * 
     * @return void
     */
    public function test_if_can_update_a_spending_without_payload(): void
    {
        $this->putJson(route('spendings.update', $this->spending->id))->assertOk();
    }

    /**
     * Test if can throw 404 if spending doesn't exists.
     * 
     * @return void
     */
    public function test_if_can_throw_not_found_if_spending_doesnt_exists(): void
    {
        $this->putJson(route('spendings.update', 9999999))->assertNotFound();
    }

    /**
     * Test if can't update a spending that doesnt belongs to user spaces.
     * 
     * @return void
     */
    public function test_if_cant_update_a_spending_that_doesnt_belongs_to_user_spaces(): void
    {
        $this->putJson(route('spendings.update', $this->createDummySpending()->id))
            ->assertForbidden()
            ->assertSee('Esta conta n\u00e3o pertence \u00e0 nenhum dos seus espa\u00e7os. Nenhuma opera\u00e7\u00e3o pode ser realizada.');
    }

    /**
     * Test if can't update a spending category with category that doesn't belongs to user space.
     * 
     * @return void
     */
    public function test_if_cant_update_a_spending_category_with_category_that_doesnt_belongs_to_user_space(): void
    {
        $this->putJson(route('spendings.update', $this->spending->id), [
            'category_id' => $this->createDummyCategory()->id,
        ])->assertForbidden()
            ->assertSee('Esta categoria n\u00e3o pertence \u00e0 nenhum dos seus espa\u00e7os. Nenhuma opera\u00e7\u00e3o pode ser realizada.');;
    }

    /**
     * Test if can't update a spending space with space that doesn't belongs to user.
     * 
     * @return void
     */
    public function test_if_cant_update_a_spending_space_with_space_that_doesnt_belongs_to_user(): void
    {
        $this->putJson(route('spendings.update', $this->spending->id), [
            'space_id' => $this->createDummySpace()->id,
        ])->assertForbidden()
            ->assertSee('O espa\u00e7o n\u00e3o pertence ao seu us\u00e1rio. Nenhuma opera\u00e7\u00e3o pode ser realizada.');
    }

    /**
     * Test if can update a spending with correctly payload.
     * 
     * @return void
     */
    public function test_if_can_update_a_spending_with_correctly_payload(): void
    {
        $this->putJson(route('spendings.update', $this->spending->id), $this->getValidSpendingPayload())->assertOk();
    }

    /**
     * Test if can save updated spending data on database.
     * 
     * @return void
     */
    public function test_if_can_save_payload_correctly_on_database(): void
    {
        $this->putJson(route('spendings.update', $this->spending->id), $data = $this->getValidSpendingPayload())->assertOk();

        $this->assertDatabaseHas('spendings', [
            'id' => $this->spending->id,
            'description' => $data['description'],
            'amount' => $data['amount'],
            'category_id' => $data['category_id'],
            'space_id' => $data['space_id'],
            'when' => $data['when'],
        ]);
    }

    /**
     * Test if can retrieve correctly json spending structure on update.
     * 
     * @return void
     */
    public function test_if_can_retrieve_correctly_json_spending_structure_on_update(): void
    {
        $this->putJson(route('spendings.update', $this->spending->id), $this->getValidSpendingPayload())
            ->assertOk()
            ->assertJsonStructure([
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
     * Test if can retrieve correctly json updated spending.
     * 
     * @return void
     */
    public function test_if_can_retrieve_correctly_json_updated_spending(): void
    {
        $this->putJson(route('spendings.update', $this->spending->id), $data = $this->getValidSpendingPayload())->assertOk()->assertJson([
            'data' => [
                'description' => $data['description'],
                'amount' => formatCurrency($data['amount'], $this->space->currency->iso),
                'when' => $data['when'],
                'space' => [
                    'id' => $data['space_id'],
                ],
            ],
        ]);
    }
}