<?php

namespace Tests\Feature\Http\Categories;

use Illuminate\Support\Facades\DB;
use Tests\TestCase;
use Tests\Traits\{HasDummyCategory, HasDummySpace, HasDummyTag, HasDummyUser};

class CategoryDestroyTest extends TestCase
{
    use HasDummyTag;
    use HasDummyUser;
    use HasDummySpace;
    use HasDummyCategory;

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
     * The dummy category.
     *
     * @var \App\Models\Category
     */
    private $category;

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
        $this->category = $this->createDummyCategoryTo($this->space);
    }

    /**
     * Test if can throw 404 if category doesn't exists.
     *
     * @return void
     */
    public function test_if_can_throw_not_found_if_category_doesnt_exists(): void
    {
        $this->deleteJson(route('categories.destroy', 999999999))->assertNotFound();
    }

    /**
     * Test if can't destroy another user space category.
     *
     * @return void
     */
    public function test_if_cant_destroy_another_user_space_category(): void
    {
        $this->deleteJson(route('categories.destroy', $this->createDummyCategory()->id))
            ->assertForbidden()
            ->assertSee('Esta categoria n\u00e3o pertence \u00e0 nenhum dos seus espa\u00e7os. Nenhuma opera\u00e7\u00e3o pode ser realizada.');
    }

    /**
     * Test if can destroy own space category.
     *
     * @return void
     */
    public function test_if_can_destroy_own_space_category(): void
    {
        $this->deleteJson(route('categories.destroy', $this->category->id))->assertOk();
    }

    /**
     * Test if can soft delete category.
     *
     * @return void
     */
    public function test_if_can_soft_delete_category(): void
    {
        $this->assertNull(
            DB::table('categories')->whereId($this->category->id)->value('deleted_at')
        );

        $this->deleteJson(route('categories.destroy', $this->category->id))->assertOk();

        $this->assertNotNull(
            DB::table('categories')->whereId($this->category->id)->value('deleted_at')
        );
    }

    /**
     * Test if can create a new activity on category deletion.
     *
     * @return void
     */
    public function test_if_can_create_a_new_activity_on_category_deletion(): void
    {
        $this->deleteJson(route('categories.destroy', $this->category->id))->assertOk();

        $this->assertDatabaseHas('activities', [
            'activitable_type' => $this->category::class,
            'activitable_id' => $this->category->id,
            'action' => 'category.deleted',
        ]);
    }

    /**
     * Test if can remove from index on deletion.
     *
     * @return void
     */
    public function test_if_can_remove_from_index_route_on_deletion(): void
    {
        $this->getJson(route('categories.index'))->assertOk()->assertJsonCount(1, 'data');

        $this->deleteJson(route('categories.destroy', $this->category->id))->assertOk();

        $this->getJson(route('categories.index'))->assertOk()->assertJsonCount(0, 'data');
    }
}
