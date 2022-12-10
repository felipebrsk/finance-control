<?php

namespace Tests\Feature\Http\Tags;

use Tests\TestCase;
use Illuminate\Support\Facades\DB;
use Tests\Traits\{HasDummyTag, HasDummyUser};

class TagDestroyTest extends TestCase
{
    use HasDummyTag;
    use HasDummyUser;

    /**
     * The dummy user.
     * 
     * @var \App\Models\User
     */
    private $user;
    
    /**
     * The dummy tag.
     * 
     * @var \App\Models\Tag
     */
    private $tag;

    /**
     * Setup new test environments.
     * 
     * @return void
     */
    public function setUp(): void
    {
        parent::setUp();

        $this->user = $this->actingAsDummyUser();
        $this->tag = $this->createDummyTagTo($this->user);
    }

    /**
     * Test if can throw 404 if tag doesn't exists.
     * 
     * @return void
     */
    public function test_if_can_throw_not_found_if_tag_doesnt_exists(): void
    {
        $this->deleteJson(route('tags.destroy', 999999999))->assertNotFound();
    }

    /**
     * Test if can't destroy another user tag.
     * 
     * @return void
     */
    public function test_if_cant_destroy_another_user_tag(): void
    {
        $this->deleteJson(route('tags.destroy', $this->createDummyTag()->id))
            ->assertForbidden()
            ->assertSee('Uma ou mais tags n\u00e3o pertencem ao seu usu\u00e1rio. Nenhuma opera\u00e7\u00e3o pode ser feita. Tente criar uma nova tag e repetir o processo.');
    }

    /**
     * Test if can destroy own space tag.
     * 
     * @return void
     */
    public function test_if_can_destroy_own_user_tag(): void
    {
        $this->deleteJson(route('tags.destroy', $this->tag->id))->assertOk();
    }

    /**
     * Test if can soft delete tag.
     * 
     * @return void
     */
    public function test_if_can_soft_delete_tag(): void
    {
        $this->assertNull(
            DB::table('tags')->whereId($this->tag->id)->value('deleted_at')
        );

        $this->deleteJson(route('tags.destroy', $this->tag->id))->assertOk();

        $this->assertNotNull(
            DB::table('tags')->whereId($this->tag->id)->value('deleted_at')
        );
    }

    /**
     * Test if can remove tag from index on deletion.
     * 
     * @return void
     */
    public function test_if_can_remove_tag_from_index_route_on_deletion(): void
    {
        $this->getJson(route('tags.index'))->assertOk()->assertJsonCount(1, 'data');

        $this->deleteJson(route('tags.destroy', $this->tag->id))->assertOk();

        $this->getJson(route('tags.index'))->assertOk()->assertJsonCount(0, 'data');
    }
}
