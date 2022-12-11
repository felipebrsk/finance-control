<?php

namespace Tests\Feature\Http\Auth;

use Tests\TestCase;
use Tests\Traits\HasDummyUser;

class MeTest extends TestCase
{
    use HasDummyUser;

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
     * Test if can access the me route.
     *
     * @return void
     */
    public function test_if_can_access_the_me_route(): void
    {
        $this->getJson(route('me'))->assertOk();
    }

    /**
     * Test if can get correctly json structure.
     *
     * @return void
     */
    public function test_if_can_get_correctly_json_structure(): void
    {
        $this->getJson(route('me'))->assertOk()->assertJsonStructure([
            'data' => [
                'id',
                'name',
                'username',
                'email',
                'avatar',
                'weekly_report',
                'first_day_of_week',
            ]
        ]);
    }

    /**
     * Test if can get correctly user.
     *
     * @return void
     */
    public function test_if_can_get_correctly_user(): void
    {
        $this->getJson(route('me'))->assertOk()->assertJson([
            'data' => [
                'id' => $this->user->id,
                'name' => $this->user->name,
                'username' => $this->user->username,
                'email' => $this->user->email,
                'avatar' => $this->user->avatar,
                'weekly_report' => $this->user->weekly_report,
                'first_day_of_week' => $this->user->first_day_of_week,
            ]
        ]);
    }

    /**
     * Test if can get correctly json attributes count.
     *
     * @return void
     */
    public function test_if_can_get_correctly_json_attributes_count(): void
    {
        $this->getJson(route('me'))->assertOk()->assertJsonCount(7, 'data');
    }
}
