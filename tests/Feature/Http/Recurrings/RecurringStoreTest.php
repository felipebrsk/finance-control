<?php

namespace Tests\Feature\Http\Recurrings;

use Tests\TestCase;
use Illuminate\Support\Carbon;
use App\Models\{Currency, Recurring};
use Tests\Traits\{
    HasDummyCategory,
    HasDummySpace,
    HasDummyRecurring,
    HasDummyTag,
    HasDummyUser
};

class RecurringStoreTest extends TestCase
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
            ]
        ];
    }

    /**
     * Test if can't create a recurring without payload.
     *
     * @return void
     */
    public function test_if_cant_create_a_recurring_without_payload(): void
    {
        $this->postJson(route('recurrings.store'))
            ->assertUnprocessable()
            ->assertSee('The description field is required. (and 6 more errors)');
    }

    /**
     * Test if can't create a recurring with category that doesn't belongs to user.
     *
     * @return void
     */
    public function test_if_cant_create_a_recurring_with_category_that_doesnt_belongs_to_user(): void
    {
        $this->postJson(route('recurrings.store'), [
            'description' => fake()->text(),
            'amount' => fake()->numberBetween(100, 29900),
            'type' => fake()->randomElement(['spending', 'earning']),
            'interval' => fake()->randomElement(['daily', 'weekly', 'biweekly', 'monthly', 'yearly']),
            'start_date' => Carbon::today(),
            'currency_id' => Currency::whereIso('BRL')->value('id'),
            'category_id' => $this->createDummyCategory()->id,
            'space_id' => $this->space->id,
        ])->assertForbidden()
            ->assertSee('Esta categoria n\u00e3o pertence \u00e0 nenhum dos seus espa\u00e7os. Nenhuma opera\u00e7\u00e3o pode ser realizada.');
    }

    /**
     * Test if can't create a recurring with space that doesn't belongs to user.
     *
     * @return void
     */
    public function test_if_cant_create_a_recurring_with_space_that_doesnt_belongs_to_user(): void
    {
        $this->postJson(route('recurrings.store'), [
            'description' => fake()->text(),
            'amount' => fake()->numberBetween(100, 29900),
            'type' => fake()->randomElement(['spending', 'earning']),
            'interval' => fake()->randomElement(['daily', 'weekly', 'biweekly', 'monthly', 'yearly']),
            'start_date' => Carbon::today(),
            'currency_id' => Currency::whereIso('BRL')->value('id'),
            'category_id' => $this->createDummyCategory()->id,
            'space_id' => $this->createDummySpace()->id,
        ])->assertForbidden()
            ->assertSee('O espa\u00e7o n\u00e3o pertence ao seu us\u00e1rio. Nenhuma opera\u00e7\u00e3o pode ser realizada.');
    }

    /**
     * Test if can't associated tags that doesn't belongs to user to recurring.
     *
     * @return void
     */
    public function test_if_cant_associate_tags_that_doesnt_belongs_to_user_to_recurring(): void
    {
        $this->postJson(route('recurrings.store'), [
            'description' => fake()->text(),
            'amount' => fake()->numberBetween(100, 29900),
            'type' => fake()->randomElement(['spending', 'earning']),
            'interval' => fake()->randomElement(['daily', 'weekly', 'biweekly', 'monthly', 'yearly']),
            'start_date' => Carbon::today(),
            'currency_id' => Currency::whereIso('BRL')->value('id'),
            'category_id' => $this->createDummyCategoryTo($this->space)->id,
            'space_id' => $this->space->id,
            'tags' => [
                $this->createDummyTag()->id,
                $this->createDummyTag()->id,
            ]
        ])->assertForbidden()
            ->assertSee('Uma ou mais tags n\u00e3o pertencem ao seu usu\u00e1rio. Nenhuma opera\u00e7\u00e3o pode ser feita. Tente criar uma nova tag e repetir o processo.');
    }

    /**
     * Test if can create a recurring with correctly payload.
     *
     * @return void
     */
    public function test_if_can_create_a_recurring_with_correctly_payload(): void
    {
        $this->postJson(route('recurrings.store'), $this->getValidRecurringPayload())->assertCreated();
    }

    /**
     * Test if can create correctly recurring in database.
     *
     * @return void
     */
    public function test_if_can_create_correctly_recurring_in_database(): void
    {
        $this->postJson(route('recurrings.store'), $data = $this->getValidRecurringPayload())->assertCreated();

        $this->assertDatabaseCount('recurrings', 2)
            ->assertDatabaseHas('recurrings', [
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
     * Test if can associate the tags in database.
     *
     * @return void
     */
    public function test_if_can_associate_the_tags_in_database(): void
    {
        $id = $this->postJson(route('recurrings.store'), $data = $this->getValidRecurringPayload())->assertCreated()->json('data')['id'];

        $this->assertDatabaseCount('taggable_tags', count($data['tags']));

        foreach ($data['tags'] as $tagId) {
            $this->assertDatabaseHas('taggable_tags', [
                'taggable_type' => Recurring::class,
                'taggable_id' => $id,
                'tag_id' => $tagId
            ]);
        }
    }

    /**
     * Test if can return correctly json recurring structure.
     *
     * @return void
     */
    public function test_if_can_return_correctly_json_recurring_structure(): void
    {
        $this->postJson(route('recurrings.store'), $this->getValidRecurringPayload())->assertCreated()->assertJsonStructure([
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
     * Test if can return correctly json recurring on creation.
     *
     * @return void
     */
    public function test_if_can_return_correctly_json_recurring_on_creation(): void
    {
        $this->postJson(route('recurrings.store'), $data = $this->getValidRecurringPayload())->assertCreated()->assertJson([
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
     * Test if can create a new activity on spending creation.
     *
     * @return void
     */
    public function test_if_can_create_a_new_activity_on_spending_creation(): void
    {
        $id = $this->postJson(route('recurrings.store'), $this->getValidRecurringPayload())->assertCreated()->json('data')['id'];

        $this->assertDatabaseHas('activities', [
            'activitable_type' => Recurring::class,
            'activitable_id' => $id,
            'action' => 'recurring.created',
        ]);
    }
}
