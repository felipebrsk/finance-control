<?php

namespace Tests\Feature\Http\Spendings;

use Tests\TestCase;
use Tests\Traits\{HasDummySpace, HasDummySpending, HasDummyTag, HasDummyUser};

class SpendingShowTest extends TestCase
{
    use HasDummyTag;
    use HasDummyUser;
    use HasDummySpace;
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
     * The dummy tag.
     * 
     * @var \App\Models\Tag
     */
    private $tag;

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
        $this->tag = $this->createDummyTag();
        $this->spending->tags()->save($this->tag)->save();
    }

    /**
     * Test if can get a spending details.
     * 
     * @return void
     */
    public function test_if_can_get_a_spending_details(): void
    {
        $this->getJson(route('spendings.show', $this->spending->id))->assertOk();
    }

    /**
     * Test if can throw 404 if spending doesn't exists.
     * 
     * @return void
     */
    public function test_if_can_throw_not_found_if_spending_doesnt_exists(): void
    {
        $this->getJson(route('spendings.show', 999999))->assertNotFound();
    }

    /**
     * Test if can't get a spending details if doesn't belongs to user space.
     * 
     * @return void
     */
    public function test_if_cant_get_a_spending_details_if_doesnt_belongs_to_user_space(): void
    {
        $spending = $this->createDummySpending();

        $this->getJson(route('spendings.show', $spending->id))
            ->assertForbidden()
            ->assertSee('Esta conta n\u00e3o pertence \u00e0 nenhum dos seus espa\u00e7os. Nenhuma opera\u00e7\u00e3o pode ser realizada.');
    }

    /**
     * Test if can get a correctly spending details json structure.
     * 
     * @return void
     */
    public function test_if_can_get_a_correctly_spending_details_json_structure(): void
    {
        $this->getJson(route('spendings.show', $this->spending->id))->assertOk()->assertJsonStructure([
            'data' => [
                'id',
                'description',
                'amount',
                'when',
                'created_at',
                'updated_at',
                'category' => [
                    'id',
                    'name',
                    'slug',
                    'created_at',
                    'updated_at',
                ],
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
                'recurring' => [
                    'id',
                    'description',
                    'amount',
                    'type',
                    'interval',
                    'start_date',
                    'end_date',
                    'last_used_date',
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
                'import' => [],
                'tags' => [
                    '*' => [
                        'id',
                        'name',
                        'slug',
                        'color',
                        'created_at',
                        'updated_at',
                    ],
                ],
            ],
        ]);
    }

    /**
     * Test if can get the correctly spending details data.
     * 
     * @return void
     */
    public function test_if_can_get_the_correctly_spending_details_data(): void
    {
        $this->getJson(route('spendings.show', $this->spending->id))->assertOk()->assertJson([
            'data' => [
                'id' => $this->spending->id,
                'description' => $this->spending->description,
                'amount' => formatCurrency($this->spending->amount, $this->spending->space->currency->iso),
                'when' => $this->spending->when,
                'created_at' => $this->spending->created_at->toIsoString(),
                'updated_at' => $this->spending->updated_at->toIsoString(),
                'category' => [
                    'id' => $this->spending->category->id,
                    'name' => $this->spending->category->name,
                    'slug' => $this->spending->category->slug,
                    'created_at' => $this->spending->category->created_at->toIsoString(),
                    'updated_at' => $this->spending->category->updated_at->toIsoString(),
                ],
                'space' => [
                    'id' => $this->spending->space->id,
                    'name' => $this->spending->space->name,
                    'slug' => $this->spending->space->slug,
                    'created_at' => $this->spending->space->created_at->toIsoString(),
                    'updated_at' => $this->spending->space->updated_at->toIsoString(),
                    'currency' => [
                        'id' => $this->spending->space->currency->id,
                        'name' => $this->spending->space->currency->name,
                        'iso' => $this->spending->space->currency->iso,
                        'symbol' => $this->spending->space->currency->symbol,
                        'created_at' => $this->spending->space->currency->created_at->toIsoString(),
                        'updated_at' => $this->spending->space->currency->updated_at->toIsoString(),
                    ],
                ],
                'recurring' => [
                    'id' => $this->spending->recurring->id,
                    'description' => $this->spending->recurring->description,
                    'amount' => formatCurrency($this->spending->recurring->amount, $this->spending->space->currency->iso),
                    'type' => $this->spending->recurring->type,
                    'interval' => $this->spending->recurring->interval,
                    'start_date' => $this->spending->recurring->start_date->toIsoString(),
                    'end_date' => $this->spending->recurring->end_date?->toIsoString(),
                    'last_used_date' => $this->spending->recurring->last_used_date,
                    'created_at' => $this->spending->recurring->created_at->toIsoString(),
                    'updated_at' => $this->spending->recurring->updated_at->toIsoString(),
                    'currency' => [
                        'id' => $this->spending->recurring->currency->id,
                        'name' => $this->spending->recurring->currency->name,
                        'iso' => $this->spending->recurring->currency->iso,
                        'symbol' => $this->spending->recurring->currency->symbol,
                        'created_at' => $this->spending->recurring->currency->created_at->toIsoString(),
                        'updated_at' => $this->spending->recurring->currency->updated_at->toIsoString(),
                    ],
                ],
                'import' => null,
                'tags' => [
                    [
                        'id' => $this->tag->id,
                        'name' => $this->tag->name,
                        'slug' => $this->tag->slug,
                        'color' => $this->tag->color,
                        'created_at' => $this->tag->created_at->toIsoString(),
                        'updated_at' => $this->tag->updated_at->toIsoString(),
                    ],
                ],
            ],
        ]);
    }
}
