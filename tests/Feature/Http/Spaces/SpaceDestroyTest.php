<?php

namespace Tests\Feature\Http\Spaces;

use Illuminate\Support\Facades\DB;
use Tests\TestCase;
use Tests\Traits\{
    HasDummyCurrency,
    HasDummySpace,
    HasDummyTag,
    HasDummyUser
};

class SpaceDestroyTest extends TestCase
{
    use HasDummyTag;
    use HasDummyUser;
    use HasDummySpace;
    use HasDummyCurrency;

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
     * Test if can throw 404 if space doesn't exists.
     *
     * @return void
     */
    public function test_if_can_throw_not_found_if_space_doesnt_exists(): void
    {
        $this->deleteJson(route('spaces.destroy', 999999999))->assertNotFound();
    }

    /**
     * Test if can't destroy another user space space.
     *
     * @return void
     */
    public function test_if_cant_destroy_another_user_space_space(): void
    {
        $this->deleteJson(route('spaces.destroy', $this->createDummySpace()->id))
            ->assertForbidden()
            ->assertSee('O espa\u00e7o n\u00e3o pertence ao seu us\u00e1rio. Nenhuma opera\u00e7\u00e3o pode ser realizada.');
    }

    /**
     * Test if can destroy own space space.
     *
     * @return void
     */
    public function test_if_can_destroy_own_space_space(): void
    {
        $this->deleteJson(route('spaces.destroy', $this->space->id))->assertOk();
    }

    /**
     * Test if can soft delete space.
     *
     * @return void
     */
    public function test_if_can_soft_delete_space(): void
    {
        $this->assertNull(
            DB::table('spaces')->whereId($this->space->id)->value('deleted_at')
        );

        $this->deleteJson(route('spaces.destroy', $this->space->id))->assertOk();

        $this->assertNotNull(
            DB::table('spaces')->whereId($this->space->id)->value('deleted_at')
        );
    }

    /**
     * Test if can remove from index on deletion.
     *
     * @return void
     */
    public function test_if_can_remove_from_index_route_on_deletion(): void
    {
        $this->getJson(route('spaces.index'))->assertOk()->assertJsonCount(2, 'data');

        $this->deleteJson(route('spaces.destroy', $this->space->id))->assertOk();

        $this->getJson(route('spaces.index'))->assertOk()->assertJsonCount(1, 'data');
    }

    /**
     * Test if can delete the tags on space deletion.
     *
     * @return void
     */
    public function test_if_can_delete_the_tags_on_space_deletion(): void
    {
        $this->space->tags()->save($this->createDummyTagTo($this->user))->save();

        $this->assertDatabaseCount('taggable_tags', 1);

        $this->deleteJson(route('spaces.destroy', $this->space->id))->assertOk();

        $this->assertDatabaseCount('taggable_tags', 0);
    }
}
