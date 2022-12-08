<?php

namespace Tests\Feature\Http\earnings;

use Illuminate\Support\Facades\DB;
use Tests\TestCase;
use Tests\Traits\{HasDummySpace, HasDummyearning, HasDummyUser};

class earningDestroyTest extends TestCase
{
    use HasDummyUser;
    use HasDummySpace;
    use HasDummyearning;

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
    }

    /**
     * Test if can throw 404 if earning doesn't exists.
     * 
     * @return void
     */
    public function test_if_can_throw_not_found_if_earning_doesnt_exists(): void
    {
        $this->deleteJson(route('earnings.destroy', 999999999))->assertNotFound();
    }

    /**
     * Test if can't destroy another user space earning.
     * 
     * @return void
     */
    public function test_if_cant_destroy_another_user_space_earning(): void
    {
        $this->deleteJson(route('earnings.destroy', $this->createDummyEarning()->id))
            ->assertForbidden()
            ->assertSee('Esta conta n\u00e3o pertence \u00e0 nenhum dos seus espa\u00e7os. Nenhuma opera\u00e7\u00e3o pode ser realizada.');
    }

    /**
     * Test if can destroy own space earning.
     * 
     * @return void
     */
    public function test_if_can_destroy_own_space_earning(): void
    {
        $this->deleteJson(route('earnings.destroy', $this->earning->id))->assertOk();
    }

    /**
     * Test if can soft delete earning.
     * 
     * @return void
     */
    public function test_if_can_soft_delete_earning(): void
    {
        $this->assertNull(
            DB::table('earnings')->whereId($this->earning->id)->value('deleted_at')
        );

        $this->deleteJson(route('earnings.destroy', $this->earning->id))->assertOk();

        $this->assertNotNull(
            DB::table('earnings')->whereId($this->earning->id)->value('deleted_at')
        );
    }

    /**
     * Test if can create a new activity on earning deletion.
     * 
     * @return void
     */
    public function test_if_can_create_a_new_activity_on_earning_deletion(): void
    {
        $this->deleteJson(route('earnings.destroy', $this->earning->id))->assertOk();

        $this->assertDatabaseHas('activities', [
            'activitable_type' => $this->earning::class,
            'activitable_id' => $this->earning->id,
            'action' => 'transaction.deleted',
        ]);
    }

    /**
     * Test if can remove from index on deletion.
     * 
     * @return void
     */
    public function test_if_can_remove_from_index_route_on_deletion(): void
    {
        $this->getJson(route('earnings.index'))->assertOk()->assertJsonCount(1, 'data');

        $this->deleteJson(route('earnings.destroy', $this->earning->id))->assertOk();

        $this->getJson(route('earnings.index'))->assertOk()->assertJsonCount(0, 'data');
    }
}
