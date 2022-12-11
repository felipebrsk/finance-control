<?php

namespace Tests\Feature\Http\Recurrings;

use Illuminate\Support\Facades\DB;
use Tests\TestCase;
use Tests\Traits\{HasDummySpace, HasDummyRecurring, HasDummyTag, HasDummyUser};

class RecurringDestroyTest extends TestCase
{
    use HasDummyTag;
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
     * Test if can throw 404 if recurring doesn't exists.
     *
     * @return void
     */
    public function test_if_can_throw_not_found_if_recurring_doesnt_exists(): void
    {
        $this->deleteJson(route('recurrings.destroy', 999999999))->assertNotFound();
    }

    /**
     * Test if can't destroy another user space recurring.
     *
     * @return void
     */
    public function test_if_cant_destroy_another_user_space_recurring(): void
    {
        $this->deleteJson(route('recurrings.destroy', $this->createDummyRecurring()->id))
            ->assertForbidden()
            ->assertSee('Esta conta n\u00e3o pertence \u00e0 nenhum dos seus espa\u00e7os. Nenhuma opera\u00e7\u00e3o pode ser realizada.');
    }

    /**
     * Test if can destroy own space recurring.
     *
     * @return void
     */
    public function test_if_can_destroy_own_space_recurring(): void
    {
        $this->deleteJson(route('recurrings.destroy', $this->recurring->id))->assertOk();
    }

    /**
     * Test if can soft delete recurring.
     *
     * @return void
     */
    public function test_if_can_soft_delete_recurring(): void
    {
        $this->assertNull(
            DB::table('recurrings')->whereId($this->recurring->id)->value('deleted_at')
        );

        $this->deleteJson(route('recurrings.destroy', $this->recurring->id))->assertOk();

        $this->assertNotNull(
            DB::table('recurrings')->whereId($this->recurring->id)->value('deleted_at')
        );
    }

    /**
     * Test if can create a new activity on recurring deletion.
     *
     * @return void
     */
    public function test_if_can_create_a_new_activity_on_recurring_deletion(): void
    {
        $this->deleteJson(route('recurrings.destroy', $this->recurring->id))->assertOk();

        $this->assertDatabaseHas('activities', [
            'activitable_type' => $this->recurring::class,
            'activitable_id' => $this->recurring->id,
            'action' => 'recurring.deleted',
        ]);
    }

    /**
     * Test if can remove from index on deletion.
     *
     * @return void
     */
    public function test_if_can_remove_from_index_route_on_deletion(): void
    {
        $this->getJson(route('recurrings.index'))->assertOk()->assertJsonCount(1, 'data');

        $this->deleteJson(route('recurrings.destroy', $this->recurring->id))->assertOk();

        $this->getJson(route('recurrings.index'))->assertOk()->assertJsonCount(0, 'data');
    }

    /**
     * Test if can delete the tags on recurring deletion.
     *
     * @return void
     */
    public function test_if_can_delete_the_tags_on_recurring_deletion(): void
    {
        $this->recurring->tags()->save($this->createDummyTagTo($this->user))->save();

        $this->assertDatabaseCount('taggable_tags', 1);

        $this->deleteJson(route('recurrings.destroy', $this->recurring->id))->assertOk();

        $this->assertDatabaseCount('taggable_tags', 0);
    }
}
