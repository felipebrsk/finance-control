<?php

namespace Tests\Feature\Http\Activities;

use Tests\TestCase;
use Tests\Traits\{
    HasDummyUser,
    HasDummySpace,
    HasDummyActivity,
    HasDummyCategory,
    HasDummyEarning,
    HasDummyRecurring,
    HasDummySpending
};

class ActivityIndexTest extends TestCase
{
    use HasDummyUser;
    use HasDummySpace;
    use HasDummyActivity;
    use HasDummyRecurring;
    use HasDummyCategory;
    use HasDummySpending;
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
     * Test if can get my activities.
     * 
     * @return void
     */
    public function test_if_can_get_my_activities(): void
    {
        $this->getJson(route('me.activities'))->assertOk();
    }

    /**
     * Test if can get correctly activities count.
     * 
     * @return void
     */
    public function test_if_can_get_correctly_activities_count(): void
    {
        $this->getJson(route('me.activities'))->assertOk()->assertJsonCount(0, 'data');

        $this->createDummyActivities($count = 3, [
            'space_id' => $this->space->id,
        ]);

        $this->getJson(route('me.activities'))->assertOk()->assertJsonCount($count, 'data');
    }

    /**
     * Test if can get correctly activities count with events.
     * 
     * @return void
     */
    public function test_if_can_get_correctly_activities_count_with_activities(): void
    {
        $this->getJson(route('me.activities'))->assertOk()->assertJsonCount(0, 'data');

        $this->createDummyRecurring([
            'space_id' => $this->space->id,
        ]);

        $this->getJson(route('me.activities'))->assertOk()->assertJsonCount(1, 'data');

        $this->createDummyCategory([
            'space_id' => $this->space->id,
        ]);

        $this->getJson(route('me.activities'))->assertOk()->assertJsonCount(2, 'data');

        $this->createDummySpending([
            'space_id' => $this->space->id,
        ]);

        $this->getJson(route('me.activities'))->assertOk()->assertJsonCount(3, 'data');

        $this->createDummyEarning([
            'space_id' => $this->space->id,
        ]);

        $this->getJson(route('me.activities'))->assertOk()->assertJsonCount(4, 'data');
    }

    /**
     * Test if can get correctly json structure.
     * 
     * @return void
     */
    public function test_if_can_get_correctly_json_structure(): void
    {
        $this->createDummyActivities(3, [
            'space_id' => $this->space->id,
        ]);

        $this->getJson(route('me.activities'))->assertOk()->assertJsonStructure([
            'data' => [
                '*' => [
                    'action',
                    'created_at',
                    'space' => [
                        'id',
                        'name',
                    ],
                ],
            ],
        ]);
    }

    /**
     * Test if can create a new activity on recurring creation.
     * 
     * @return void
     */
    public function test_if_can_create_a_new_activity_on_recurring_creation(): void
    {
        $this->assertDatabaseCount('activities', 5); # consider seedings

        $this->createDummyRecurring();

        $this->assertDatabaseCount('activities', 7); # category is created in factory as well
    }

    /**
     * Test if can create a new activity on category creation.
     * 
     * @return void
     */
    public function test_if_can_create_a_new_activity_on_category_creation(): void
    {
        $this->assertDatabaseCount('activities', 5); # consider seedings

        $this->createDummyCategory();

        $this->assertDatabaseCount('activities', 6);
    }

    /**
     * Test if can create a new activity on spending creation.
     * 
     * @return void
     */
    public function test_if_can_create_a_new_activity_on_spending_creation(): void
    {
        $this->assertDatabaseCount('activities', 5); # consider seedings

        $this->createDummySpending();

        $this->assertDatabaseCount('activities', 9); # recurring and category are created in factory as well
    }

    /**
     * Test if can create a new activity on earning creation.
     * 
     * @return void
     */
    public function test_if_can_create_a_new_activity_on_earning_creation(): void
    {
        $this->assertDatabaseCount('activities', 5); # consider seedings

        $this->createDummyEarning();

        $this->assertDatabaseCount('activities', 9); # recurring and category are created in factory as well
    }
}
