<?php

namespace Tests\Feature\Http\Spaces;

use Tests\TestCase;
use Tests\Traits\{HasDummySpace, HasDummyUser};

class SpaceIndexTest extends TestCase
{
    use HasDummyUser;
    use HasDummySpace;

    /**
     * The dummy user.
     * 
     * @var \App\Models\User
     */
    private $user;

    /**
     * Setup new test environments.
     * 
     * @return void
     */
    public function setUp(): void
    {
        parent::setUp();

        $this->user = $this->actingAsDummyUser();
    }

    /**
     * Test if can get spaces route.
     * 
     * @return void
     */
    public function test_if_can_get_spaces_route(): void
    {
        $this->getJson(route('spaces.index'))->assertOk();
    }

    /**
     * Test if can get correctly spaces json count.
     * 
     * @return void
     */
    public function test_if_can_get_correctly_spaces_json_count(): void
    {
        $this->getJson(route('spaces.index'))->assertOk()->assertJsonCount(1, 'data'); # on user creation, the default spaces is created too.

        $this->createDummySpaceTo($this->user);

        $this->getJson(route('spaces.index'))->assertOk()->assertJsonCount(2, 'data');
    }

    /**
     * Test if can get correctly spaces json structure.
     * 
     * @return void
     */
    public function test_if_can_get_correctly_spaces_json_structure(): void
    {
        $this->getJson(route('spaces.index'))->assertOk()->assertJsonStructure([
            'data' => [
                '*' => [
                    'id',
                    'name',
                    'slug',
                    'created_at',
                    'updated_at',
                    'monthly_earning_recurrings',
                    'monthly_balance',
                    'monthly_spending_recurrings',
                    'monthly_recurrings_calculated',
                ],
            ],
        ]);
    }

    /**
     * Test if can't get another user spaces on count.
     * 
     * @return void
     */
    public function test_if_cant_get_another_user_spaces_on_count(): void
    {
        $this->getJson(route('spaces.index'))->assertOk()->assertJsonCount(1, 'data'); # on user creation, the default spaces is created too.

        $this->createDummySpaces(3);

        $this->getJson(route('spaces.index'))->assertOk()->assertJsonCount(1, 'data');
    }

    /**
     * Test if can get correctly json space.
     * 
     * @return void
     */
    public function test_if_can_get_correctly_json_space(): void
    {
        foreach ($this->user->spaces as $space) {
            $this->getJson(route('spaces.index'))->assertOk()->assertJson([
                'data' => [
                    [
                        'id' => $space->id,
                        'name' => $space->name,
                        'slug' => $space->slug,
                        'monthly_earning_recurrings' => formatCurrency($space->getMonthlyEarningRecurrings(), $space->currency->iso),
                        'monthly_balance' => formatCurrency($space->getMonthlyBalance(), $space->currency->iso),
                        'monthly_spending_recurrings' => formatCurrency($space->getMonthlySpendingRecurrings(), $space->currency->iso),
                        'monthly_recurrings_calculated' => formatCurrency($space->calculateMonthlyRecurrings(), $space->currency->iso),
                        'created_at' => $space->created_at->toIsoString(),
                        'updated_at' => $space->updated_at->toIsoString(),
                    ],
                ],
            ]);
        }
    }
}
