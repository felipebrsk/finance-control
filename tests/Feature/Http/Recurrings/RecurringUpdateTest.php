<?php

namespace Tests\Feature\Http\Recurrings;

use App\Models\Currency;
use Illuminate\Support\Carbon;
use Tests\TestCase;
use Tests\Traits\{HasDummyCategory, HasDummySpace, HasDummyRecurring, HasDummyTag, HasDummyUser};

class RecurringUpdateTest extends TestCase
{
    use HasDummyTag;
    use HasDummyUser;
    use HasDummySpace;
    use HasDummyCategory;
    use HasDummyRecurring;

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
     * Setup new test environments.
     * 
     * @return void
     */
    public function setUp(): void
    {
        parent::setUp();

        $this->user = $this->actingAsDummyUser();
        $this->space = $this->createDummySpaceTo($this->user);
        $this->recurring = $this->createDummyRecurringTo($this->space);
    }

    /**
     * Get valid recurring payload.
     * 
     * @return array
     */
    protected function getValidRecurringPayload(): array
    {
        return [
            'description' => fake()->text(),
            'amount' => fake()->numberBetween(100, 29900),
            'type' => fake()->randomElement(['spending', 'earning']),
            'interval' => fake()->randomElement(['daily', 'weekly', 'biweekly', 'monthly', 'yearly']),
            'start_date' => Carbon::today()->toDateTimeString(),
            'currency_id' => Currency::whereIso('BRL')->value('id'),
            'category_id' => $this->createDummyCategoryTo($this->space)->id,
            'space_id' => $this->space->id,
            'tags' => [
                $this->createDummyTagTo($this->user)->id,
                $this->createDummyTagTo($this->user)->id,
            ],
        ];
    }

    /**
     * Test if can update a recurring without payload.
     * 
     * @return void
     */
    public function test_if_can_update_a_recurring_without_payload(): void
    {
        $this->putJson(route('recurrings.update', $this->recurring->id))->assertOk();
    }

    /**
     * Test if can throw 404 if recurring doesn't exists.
     * 
     * @return void
     */
    public function test_if_can_throw_not_found_if_recurring_doesnt_exists(): void
    {
        $this->putJson(route('recurrings.update', 9999999))->assertNotFound();
    }

    /**
     * Test if can't update a recurring that doesnt belongs to user spaces.
     * 
     * @return void
     */
    public function test_if_cant_update_a_recurring_that_doesnt_belongs_to_user_spaces(): void
    {
        $this->putJson(route('recurrings.update', $this->createDummyrecurring()->id))
            ->assertForbidden()
            ->assertSee('Esta conta n\u00e3o pertence \u00e0 nenhum dos seus espa\u00e7os. Nenhuma opera\u00e7\u00e3o pode ser realizada.');
    }

    /**
     * Test if can't update a recurring category with category that doesn't belongs to user space.
     * 
     * @return void
     */
    public function test_if_cant_update_a_recurring_category_with_category_that_doesnt_belongs_to_user_space(): void
    {
        $this->putJson(route('recurrings.update', $this->recurring->id), [
            'category_id' => $this->createDummyCategory()->id,
        ])->assertForbidden()
            ->assertSee('Esta categoria n\u00e3o pertence \u00e0 nenhum dos seus espa\u00e7os. Nenhuma opera\u00e7\u00e3o pode ser realizada.');;
    }

    /**
     * Test if can't update a recurring space with space that doesn't belongs to user.
     * 
     * @return void
     */
    public function test_if_cant_update_a_recurring_space_with_space_that_doesnt_belongs_to_user(): void
    {
        $this->putJson(route('recurrings.update', $this->recurring->id), [
            'space_id' => $this->createDummySpace()->id,
        ])->assertForbidden()
            ->assertSee('O espa\u00e7o n\u00e3o pertence ao seu us\u00e1rio. Nenhuma opera\u00e7\u00e3o pode ser realizada.');
    }

    /**
     * Test if can update a recurring with correctly payload.
     * 
     * @return void
     */
    public function test_if_can_update_a_recurring_with_correctly_payload(): void
    {
        $this->putJson(route('recurrings.update', $this->recurring->id), $this->getValidRecurringPayload())->assertOk();
    }

    /**
     * Test if can save updated recurring data in database.
     * 
     * @return void
     */
    public function test_if_can_save_payload_correctly_in_database(): void
    {
        $this->putJson(route('recurrings.update', $this->recurring->id), $data = $this->getValidRecurringPayload())->assertOk();

        $this->assertDatabaseHas('recurrings', [
            'description' => $data['description'],
            'category_id' => $data['category_id'],
            'amount' => $data['amount'],
            'start_date' => $data['start_date'],
            'space_id' => $data['space_id'],
            'type' => $data['type'],
            'interval' => $data['interval'],
            'currency_id' => $data['currency_id'],
        ]);
    }

    /**
     * Test if can retrieve correctly json recurring structure on update.
     * 
     * @return void
     */
    public function test_if_can_retrieve_correctly_json_recurring_structure_on_update(): void
    {
        $this->putJson(route('recurrings.update', $this->recurring->id), $this->getValidRecurringPayload())
            ->assertOk()
            ->assertJsonStructure([
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
                    'currency' => [
                        'id',
                        'name',
                        'iso',
                        'symbol',
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
                ],
            ]);
    }

    /**
     * Test if can retrieve correctly json updated recurring.
     * 
     * @return void
     */
    public function test_if_can_retrieve_correctly_json_updated_recurring(): void
    {
        $this->putJson(route('recurrings.update', $this->recurring->id), $data = $this->getValidRecurringPayload())->assertOk()->assertJson([
            'data' => [
                'description' => $data['description'],
                'amount' => formatCurrency($data['amount'], Currency::findOrFail($data['currency_id'])->value('iso')),
                'type' => $data['type'],
                'interval' => $data['interval'],
                'start_date' => Carbon::parse($data['start_date'])->toISOString(),
                'end_date' => null,
                'last_used_date' => null,
                'currency' => [
                    'id' => $data['currency_id'],
                ],
                'space' => [
                    'id' => $data['space_id'],
                ],
            ],
        ]);
    }

    /**
     * Test if can create a new activity on recurring update.
     * 
     * @return void
     */
    public function test_if_can_create_a_new_activity_on_recurring_update(): void
    {
        $this->putJson(route('recurrings.update', $this->recurring->id), $this->getValidrecurringPayload())->assertOk();

        $this->assertDatabaseHas('activities', [
            'activitable_type' => $this->recurring::class,
            'activitable_id' => $this->recurring->id,
            'action' => 'recurring.updated',
        ]);
    }

    /**
     * Test if can associate new tags to recurring.
     * 
     * @return void
     */
    public function test_if_can_associate_new_tags_to_recurring(): void
    {
        $this->putJson(route('recurrings.update', $this->recurring->id), $data = $this->getValidrecurringPayload())->assertOk();

        $this->assertDatabaseCount('taggable_tags', count($data['tags']));

        foreach ($data['tags'] as $tagId) {
            $this->assertDatabaseHas('taggable_tags', [
                'taggable_type' => $this->recurring::class,
                'taggable_id' => $this->recurring->id,
                'tag_id' => $tagId
            ]);
        }
    }

    /**
     * Test if can't update recurring tags with a tag that doesn't belongs to user.
     * 
     * @return void
     */
    public function test_if_cant_update_recurring_tags_with_a_tag_that_doesnt_belongs_to_user(): void
    {
        $this->putJson(route('recurrings.update', $this->recurring->id), [
            'tags' => [
                $this->createDummyTag()->id,
                $this->createDummyTag()->id,
            ]
        ])->assertForbidden()
            ->assertSee('Uma ou mais tags n\u00e3o pertencem ao seu usu\u00e1rio. Nenhuma opera\u00e7\u00e3o pode ser feita. Tente criar uma nova tag e repetir o processo.');
    }
}
