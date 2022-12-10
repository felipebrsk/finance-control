<?php

namespace Tests\Feature\Http\Recurrings;

use Tests\TestCase;
use Tests\Traits\{HasDummyEarning, HasDummySpace, HasDummyRecurring, HasDummySpending, HasDummyTag, HasDummyUser};

class RecurringShowTest extends TestCase
{
    use HasDummyTag;
    use HasDummyUser;
    use HasDummySpace;
    use HasDummyEarning;
    use HasDummySpending;
    use HasDummyrecurring;

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
     * The dummy recurring.
     * 
     * @var \App\Models\Recurring
     */
    private $recurring;

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
        $this->recurring = $this->createDummyrecurringTo($this->space);
        $this->tag = $this->createDummyTag();
        $this->recurring->tags()->save($this->tag)->save();
    }

    /**
     * Test if can throw 404 if recurring doesn't exists.
     * 
     * @return void
     */
    public function test_if_can_throw_not_found_if_recurring_doesnt_exists(): void
    {
        $this->getJson(route('recurrings.show', 999999))->assertNotFound();
    }

    /**
     * Test if can't get a recurring details if doesn't belongs to user space.
     * 
     * @return void
     */
    public function test_if_cant_get_a_recurring_details_if_doesnt_belongs_to_user_space(): void
    {
        $recurring = $this->createDummyrecurring();

        $this->getJson(route('recurrings.show', $recurring->id))
            ->assertForbidden()
            ->assertSee('Esta conta n\u00e3o pertence \u00e0 nenhum dos seus espa\u00e7os. Nenhuma opera\u00e7\u00e3o pode ser realizada.');
    }

    /**
     * Test if can get a recurring details.
     * 
     * @return void
     */
    public function test_if_can_get_a_recurring_details(): void
    {
        $this->getJson(route('recurrings.show', $this->recurring->id))->assertOk();
    }

    /**
     * Test if can get a correctly recurring details json structure.
     * 
     * @return void
     */
    public function test_if_can_get_a_correctly_recurring_details_json_structure(): void
    {
        $this->recurring->spendings()->save($this->createDummySpending())->save();
        $this->recurring->earnings()->save($this->createDummyEarning())->save();

        $this->getJson(route('recurrings.show', $this->recurring->id))->assertOk()->assertJsonStructure([
            'data' => [
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
                'spendings' => [
                    '*' => [
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
                'earnings' => [
                    '*' => [
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
                'currency' => [
                    'id',
                    'name',
                    'iso',
                    'symbol',
                    'created_at',
                    'updated_at',
                ],
                'category' => [
                    'id',
                    'name',
                    'slug',
                    'color',
                    'created_at',
                    'updated_at',
                ],
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
     * Test if can get the correctly recurring details data.
     * 
     * @return void
     */
    public function test_if_can_get_the_correctly_recurring_details_data(): void
    {
        $this->recurring->spendings()->save($spending = $this->createDummySpending())->save();
        $this->recurring->earnings()->save($earning = $this->createDummyEarning())->save();

        $this->getJson(route('recurrings.show', $this->recurring->id))->assertOk()->assertJson([
            'data' => [
                'id' => $this->recurring->id,
                'description' => $this->recurring->description,
                'amount' => formatCurrency($this->recurring->amount, $this->recurring->currency->iso),
                'type' => $this->recurring->type,
                'interval' => $this->recurring->interval,
                'start_date' => $this->recurring->start_date->toIsoString(),
                'end_date' => $this->recurring->end_date?->toIsoString(),
                'last_used_date' => $this->recurring->last_used_date,
                'created_at' => $this->recurring->created_at->toIsoString(),
                'updated_at' => $this->recurring->updated_at->toIsoString(),
                'spendings' => [
                    [
                        'id' => $spending->id,
                        'description' => $spending->description,
                        'amount' => formatCurrency($spending->amount, $spending->space->currency->iso),
                        'when' => $spending->when,
                        'created_at' => $spending->created_at->toIsoString(),
                        'updated_at' => $spending->updated_at->toIsoString(),
                        'space' => [
                            'id' => $spending->space->id,
                            'name' => $spending->space->name,
                            'slug' => $spending->space->slug,
                            'monthly_earning_recurrings' => formatCurrency($spending->space->getMonthlyEarningRecurrings(), $spending->space->currency->iso),
                            'monthly_balance' => formatCurrency($spending->space->getMonthlyBalance(), $spending->space->currency->iso),
                            'monthly_spending_recurrings' => formatCurrency($spending->space->getMonthlySpendingRecurrings(), $spending->space->currency->iso),
                            'monthly_recurrings_calculated' => formatCurrency($spending->space->calculateMonthlyRecurrings(), $spending->space->currency->iso),
                            'created_at' => $spending->space->created_at->toIsoString(),
                            'updated_at' => $spending->space->updated_at->toIsoString(),
                            'currency' => [
                                'id' => $spending->space->currency->id,
                                'name' => $spending->space->currency->name,
                                'iso' => $spending->space->currency->iso,
                                'symbol' => $spending->space->currency->symbol,
                                'created_at' => $spending->space->currency->created_at->toIsoString(),
                                'updated_at' => $spending->space->currency->updated_at->toIsoString(),
                            ],
                        ],
                    ],
                ],
                'earnings' => [
                    [
                        'id' => $earning->id,
                        'description' => $earning->description,
                        'amount' => formatCurrency($earning->amount, $earning->space->currency->iso),
                        'when' => $earning->when,
                        'created_at' => $earning->created_at->toIsoString(),
                        'updated_at' => $earning->updated_at->toIsoString(),
                        'space' => [
                            'id' => $earning->space->id,
                            'name' => $earning->space->name,
                            'slug' => $earning->space->slug,
                            'monthly_earning_recurrings' => formatCurrency($earning->space->getMonthlyEarningRecurrings(), $earning->space->currency->iso),
                            'monthly_balance' => formatCurrency($earning->space->getMonthlyBalance(), $earning->space->currency->iso),
                            'monthly_spending_recurrings' => formatCurrency($earning->space->getMonthlySpendingRecurrings(), $earning->space->currency->iso),
                            'monthly_recurrings_calculated' => formatCurrency($earning->space->calculateMonthlyRecurrings(), $earning->space->currency->iso),
                            'created_at' => $earning->space->created_at->toIsoString(),
                            'updated_at' => $earning->space->updated_at->toIsoString(),
                            'currency' => [
                                'id' => $earning->space->currency->id,
                                'name' => $earning->space->currency->name,
                                'iso' => $earning->space->currency->iso,
                                'symbol' => $earning->space->currency->symbol,
                                'created_at' => $earning->space->currency->created_at->toIsoString(),
                                'updated_at' => $earning->space->currency->updated_at->toIsoString(),
                            ],
                        ],
                    ],
                ],
                'currency' => [
                    'id' => $this->recurring->currency->id,
                    'name' => $this->recurring->currency->name,
                    'iso' => $this->recurring->currency->iso,
                    'symbol' => $this->recurring->currency->symbol,
                    'created_at' => $this->recurring->currency->created_at->toIsoString(),
                    'updated_at' => $this->recurring->currency->updated_at->toIsoString(),
                ],
                'category' => [
                    'id' => $this->recurring->category->id,
                    'name' => $this->recurring->category->name,
                    'slug' => $this->recurring->category->slug,
                    'color' => $this->recurring->category->color,
                    'created_at' => $this->recurring->category->created_at->toIsoString(),
                    'updated_at' => $this->recurring->category->updated_at->toIsoString(),
                ],
                'space' => [
                    'id' => $this->recurring->space->id,
                    'name' => $this->recurring->space->name,
                    'slug' => $this->recurring->space->slug,
                    'monthly_earning_recurrings' => formatCurrency($this->recurring->space->getMonthlyEarningRecurrings(), $this->recurring->space->currency->iso),
                    'monthly_balance' => formatCurrency($this->recurring->space->getMonthlyBalance(), $this->recurring->space->currency->iso),
                    'monthly_spending_recurrings' => formatCurrency($this->recurring->space->getMonthlySpendingRecurrings(), $this->recurring->space->currency->iso),
                    'monthly_recurrings_calculated' => formatCurrency($this->recurring->space->calculateMonthlyRecurrings(), $this->recurring->space->currency->iso),
                    'created_at' => $this->recurring->space->created_at->toIsoString(),
                    'updated_at' => $this->recurring->space->updated_at->toIsoString(),
                    'currency' => [
                        'id' => $this->recurring->space->currency->id,
                        'name' => $this->recurring->space->currency->name,
                        'iso' => $this->recurring->space->currency->iso,
                        'symbol' => $this->recurring->space->currency->symbol,
                        'created_at' => $this->recurring->space->currency->created_at->toIsoString(),
                        'updated_at' => $this->recurring->space->currency->updated_at->toIsoString(),
                    ],
                ],
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
