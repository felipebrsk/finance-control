<?php

namespace Tests\Feature\Http\Earnings;

use Tests\TestCase;
use Tests\Traits\{HasDummySpace, HasDummyEarning, HasDummyTag, HasDummyUser};

class EarningShowTest extends TestCase
{
    use HasDummyTag;
    use HasDummyUser;
    use HasDummySpace;
    use HasDummyEarning;

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
     * The dummy earning.
     *
     * @var \App\Models\Earning
     */
    private $earning;

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
        $this->earning = $this->createDummyEarningTo($this->space);
        $this->tag = $this->createDummyTag();
        $this->earning->tags()->save($this->tag)->save();
    }

    /**
     * Test if can get a earning details.
     *
     * @return void
     */
    public function test_if_can_get_a_earning_details(): void
    {
        $this->getJson(route('earnings.show', $this->earning->id))->assertOk();
    }

    /**
     * Test if can throw 404 if earning doesn't exists.
     *
     * @return void
     */
    public function test_if_can_throw_not_found_if_earning_doesnt_exists(): void
    {
        $this->getJson(route('earnings.show', 999999))->assertNotFound();
    }

    /**
     * Test if can't get a earning details if doesn't belongs to user space.
     *
     * @return void
     */
    public function test_if_cant_get_a_earning_details_if_doesnt_belongs_to_user_space(): void
    {
        $earning = $this->createDummyEarning();

        $this->getJson(route('earnings.show', $earning->id))
            ->assertForbidden()
            ->assertSee('Esta conta n\u00e3o pertence \u00e0 nenhum dos seus espa\u00e7os. Nenhuma opera\u00e7\u00e3o pode ser realizada.');
    }

    /**
     * Test if can get a correctly earning details json structure.
     *
     * @return void
     */
    public function test_if_can_get_a_correctly_earning_details_json_structure(): void
    {
        $this->getJson(route('earnings.show', $this->earning->id))->assertOk()->assertJsonStructure([
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
                    'color',
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
     * Test if can get the correctly earning details data.
     *
     * @return void
     */
    public function test_if_can_get_the_correctly_earning_details_data(): void
    {
        $this->getJson(route('earnings.show', $this->earning->id))->assertOk()->assertJson([
            'data' => [
                'id' => $this->earning->id,
                'description' => $this->earning->description,
                'amount' => formatCurrency($this->earning->amount, $this->earning->space->currency->iso),
                'when' => $this->earning->when,
                'created_at' => $this->earning->created_at->toIsoString(),
                'updated_at' => $this->earning->updated_at->toIsoString(),
                'category' => [
                    'id' => $this->earning->category->id,
                    'name' => $this->earning->category->name,
                    'slug' => $this->earning->category->slug,
                    'created_at' => $this->earning->category->created_at->toIsoString(),
                    'updated_at' => $this->earning->category->updated_at->toIsoString(),
                ],
                'space' => [
                    'id' => $this->earning->space->id,
                    'name' => $this->earning->space->name,
                    'slug' => $this->earning->space->slug,
                    'created_at' => $this->earning->space->created_at->toIsoString(),
                    'updated_at' => $this->earning->space->updated_at->toIsoString(),
                    'currency' => [
                        'id' => $this->earning->space->currency->id,
                        'name' => $this->earning->space->currency->name,
                        'iso' => $this->earning->space->currency->iso,
                        'symbol' => $this->earning->space->currency->symbol,
                        'created_at' => $this->earning->space->currency->created_at->toIsoString(),
                        'updated_at' => $this->earning->space->currency->updated_at->toIsoString(),
                    ],
                ],
                'recurring' => [
                    'id' => $this->earning->recurring->id,
                    'description' => $this->earning->recurring->description,
                    'amount' => formatCurrency($this->earning->recurring->amount, $this->earning->space->currency->iso),
                    'type' => $this->earning->recurring->type,
                    'interval' => $this->earning->recurring->interval,
                    'start_date' => $this->earning->recurring->start_date->toIsoString(),
                    'end_date' => $this->earning->recurring->end_date?->toIsoString(),
                    'last_used_date' => $this->earning->recurring->last_used_date,
                    'created_at' => $this->earning->recurring->created_at->toIsoString(),
                    'updated_at' => $this->earning->recurring->updated_at->toIsoString(),
                    'currency' => [
                        'id' => $this->earning->recurring->currency->id,
                        'name' => $this->earning->recurring->currency->name,
                        'iso' => $this->earning->recurring->currency->iso,
                        'symbol' => $this->earning->recurring->currency->symbol,
                        'created_at' => $this->earning->recurring->currency->created_at->toIsoString(),
                        'updated_at' => $this->earning->recurring->currency->updated_at->toIsoString(),
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
                    ]
                ]
            ],
        ]);
    }
}
