<?php

namespace Tests\Feature\Http\Recurrings;

use Tests\TestCase;
use Tests\Traits\{HasDummySpace, HasDummyRecurring, HasDummyUser};

class RecurringIndexTest extends TestCase
{
    use HasDummyUser;
    use HasDummySpace;
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
     * Test if can get the recurrings route.
     * 
     * @return void
     */
    public function test_if_can_get_the_recurrings_route(): void
    {
        $this->getJson(route('recurrings.index'))->assertOk();
    }

    /**
     * Test if can get the correctly json recurrings count.
     * 
     * @return void
     */
    public function test_if_can_get_correctly_json_recurrings_count(): void
    {
        $this->getJson(route('recurrings.index'))->assertOk()->assertJsonCount(0, 'data');

        $this->createDummyRecurrings(3, [
            'space_id' => $this->space->id,
        ]);

        $this->getJson(route('recurrings.index'))->assertOk()->assertJsonCount(3, 'data');
    }

    /**
     * Test if cant get another user recurrings count.
     * 
     * @return void
     */
    public function test_if_cant_get_another_user_recurrings_count(): void
    {
        $this->getJson(route('recurrings.index'))->assertOk()->assertJsonCount(0, 'data');

        $this->createDummyRecurrings(3);

        $this->getJson(route('recurrings.index'))->assertOk()->assertJsonCount(0, 'data');
    }

    /**
     * Test if can get correctly json recurrings structure.
     * 
     * @return void
     */
    public function test_if_can_get_correctly_json_recurrings_structure(): void
    {
        $this->createDummyRecurringTo($this->space);

        $this->getJson(route('recurrings.index'))->assertOk()->assertJsonStructure([
            'data' => [
                '*' => [
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
            ],
        ]);
    }

    /**
     * Test if can get correctly json recurring.
     * 
     * @return void
     */
    public function test_if_can_get_correctly_json_recurring(): void
    {
        $recurring = $this->createDummyRecurringTo($this->space);

        $this->getJson(route('recurrings.index'))->assertOk()->assertJson([
            'data' => [
                [
                    'id' => $recurring->id,
                    'description' => $recurring->description,
                    'amount' => formatCurrency($recurring->amount, $recurring->currency->iso),
                    'type' => $recurring->type,
                    'interval' => $recurring->interval,
                    'start_date' => $recurring->start_date->toIsoString(),
                    'end_date' => $recurring->end_date?->toIsoString(),
                    'last_used_date' => $recurring->last_used_date,
                    'created_at' => $recurring->created_at->toIsoString(),
                    'updated_at' => $recurring->updated_at->toIsoString(),
                    'currency' => [
                        'id' => $recurring->currency->id,
                        'name' => $recurring->currency->name,
                        'iso' => $recurring->currency->iso,
                        'symbol' => $recurring->currency->symbol,
                        'created_at' => $recurring->currency->created_at->toIsoString(),
                        'updated_at' => $recurring->currency->updated_at->toIsoString(),
                    ],
                ],
            ],
        ]);
    }
}
