<?php

namespace Tests\Feature\Http\Earnings;

use Tests\TestCase;
use Tests\Traits\{HasDummySpace, HasDummyEarning, HasDummyUser};

class EarningIndexTest extends TestCase
{
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
     * Test if can get the earnings route.
     * 
     * @return void
     */
    public function test_if_can_get_the_earnings_route(): void
    {
        $this->getJson(route('earnings.index'))->assertOk();
    }

    /**
     * Test if can get the correctly json earnings count.
     * 
     * @return void
     */
    public function test_if_can_get_correctly_json_earnings_count(): void
    {
        $this->getJson(route('earnings.index'))->assertOk()->assertJsonCount(0, 'data');

        $this->createDummyEarnings(3, [
            'space_id' => $this->space->id,
        ]);

        $this->getJson(route('earnings.index'))->assertOk()->assertJsonCount(3, 'data');
    }

    /**
     * Test if cant get another user earnings count.
     * 
     * @return void
     */
    public function test_if_cant_get_another_user_earnings_count(): void
    {
        $this->getJson(route('earnings.index'))->assertOk()->assertJsonCount(0, 'data');

        $this->createDummyEarnings(3);

        $this->getJson(route('earnings.index'))->assertOk()->assertJsonCount(0, 'data');
    }

    /**
     * Test if can get correctly json earnings structure.
     * 
     * @return void
     */
    public function test_if_can_get_correctly_json_earnings_structure(): void
    {
        $this->createDummyEarning([
            'space_id' => $this->space->id,
        ]);

        $this->getJson(route('earnings.index'))->assertOk()->assertJsonStructure([
            'data' => [
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
        ]);
    }

    /**
     * Test if can get correctly json earning.
     * 
     * @return void
     */
    public function test_if_can_get_correctly_json_earning(): void
    {
        $earning = $this->createDummyEarning([
            'space_id' => $this->space->id,
        ]);

        $this->getJson(route('earnings.index'))->assertOk()->assertJson([
            'data' => [
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
        ]);
    }
}
