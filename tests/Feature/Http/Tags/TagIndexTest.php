<?php

namespace Tests\Feature\Http\Tags;

use Tests\TestCase;
use Tests\Traits\{HasDummyTag, HasDummyUser};

class TagIndexTest extends TestCase
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
     * Test if can get the tags route.
     *
     * @return void
     */
    public function test_if_can_get_the_tags_route(): void
    {
        $this->getJson(route('tags.index'))->assertOk();
    }

    /**
     * Test if can get the correctly json tags count.
     *
     * @return void
     */
    public function test_if_can_get_correctly_json_tags_count(): void
    {
        $this->getJson(route('tags.index'))->assertOk()->assertJsonCount(0, 'data');

        $this->createDummyTagTo($this->user);

        $this->getJson(route('tags.index'))->assertOk()->assertJsonCount(1, 'data');
    }

    /**
     * Test if cant get another user tags count.
     *
     * @return void
     */
    public function test_if_cant_get_another_user_tags_count(): void
    {
        $this->getJson(route('tags.index'))->assertOk()->assertJsonCount(0, 'data');

        $this->createDummyTags(3);

        $this->getJson(route('tags.index'))->assertOk()->assertJsonCount(0, 'data');
    }

    /**
     * Test if can get correctly json tags structure.
     *
     * @return void
     */
    public function test_if_can_get_correctly_json_tags_structure(): void
    {
        $this->createDummyTagTo($this->user);

        $this->getJson(route('tags.index'))->assertOk()->assertJsonStructure([
            'data' => [
                '*' => [
                    'id',
                    'name',
                    'slug',
                    'color',
                    'created_at',
                    'updated_at',
                ],
            ],
        ]);
    }

    /**
     * Test if can get correctly json tag.
     *
     * @return void
     */
    public function test_if_can_get_correctly_json_tag(): void
    {
        $tag = $this->createDummyTagTo($this->user);

        $this->getJson(route('tags.index'))->assertOk()->assertJson([
            'data' => [
                [
                    'id' => $tag->id,
                    'name' => $tag->name,
                    'slug' => $tag->slug,
                    'color' => $tag->color,
                    'created_at' => $tag->created_at->toIsoString(),
                    'updated_at' => $tag->updated_at->toIsoString(),
                ],
            ],
        ]);
    }
}
