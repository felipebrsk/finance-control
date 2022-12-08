<?php

namespace Tests\Feature\Http\Spendings;

use Tests\TestCase;
use Tests\Traits\{HasDummySpace, HasDummySpending, HasDummyUser};

class SpendingIndexTest extends TestCase
{
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
     * Test if can get the spendings route.
     * 
     * @return void
     */
    public function test_if_can_get_the_spendings_route(): void
    {
        $this->getJson(route('spendings.index'))->assertOk();
    }

    /**
     * Test if can get the correctly json spendings count.
     * 
     * @return void
     */
    public function test_if_can_get_correctly_json_spendings_count(): void
    {
        $this->getJson(route('spendings.index'))->assertOk()->assertJsonCount(0, 'data');

        $this->createDummySpendings(3, [
            'space_id' => $this->space->id,
        ]);

        $this->getJson(route('spendings.index'))->assertOk()->assertJsonCount(3, 'data');
    }

    /**
     * Test if cant get another user spendings count.
     * 
     * @return void
     */
    public function test_if_cant_get_another_user_spendings_count(): void
    {
        $this->getJson(route('spendings.index'))->assertOk()->assertJsonCount(0, 'data');

        $this->createDummySpendings(3);

        $this->getJson(route('spendings.index'))->assertOk()->assertJsonCount(0, 'data');
    }

    /**
     * Test if can get correctly json spendings structure.
     * 
     * @return void
     */
    public function test_if_can_get_correctly_json_spendings_structure(): void
    {
        $this->createDummySpending([
            'space_id' => $this->space->id,
        ]);

        $this->getJson(route('spendings.index'))->assertOk()->assertJsonStructure([
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
     * Test if can get correctly json spending.
     * 
     * @return void
     */
    public function test_if_can_get_correctly_json_spending(): void
    {
        $spending = $this->createDummySpending([
            'space_id' => $this->space->id,
        ]);

        $this->getJson(route('spendings.index'))->assertOk()->assertJson([
            'data' => [
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
        ]);
    }
}
