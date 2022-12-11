<?php

namespace Tests\Feature\Http\Spaces;

use Tests\TestCase;
use Tests\Traits\{
    HasDummySpace,
    HasDummyTag,
    HasDummyUser
};

class SpaceDetachTagsTest extends TestCase
{
    use HasDummyTag;
    use HasDummyUser;
    use HasDummySpace;

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
     * The dummy tag ids.
     *
     * @var \Illuminate\Database\Eloquent\Collection
     */
    private $tags;

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
        $this->tags = $this->createDummyTags(4, [
            'user_id' => $this->user->id,
        ]);
        $this->space->tags()->saveMany($this->tags);
    }

    /**
     * Get valid detach tags payload.
     *
     * @return array
     */
    protected function getValidDetachTagsPayload(): array
    {
        return [
            'tags' => $this->tags->pluck('id')->toArray()
        ];
    }

    /**
     * Test if can throw 404 if space doesn't exists.
     *
     * @return void
     */
    public function test_if_can_throw_not_found_if_space_doesnt_exists(): void
    {
        $this->deleteJson(route('space.detach.tags', 99999999), $this->getValidDetachTagsPayload())->assertNotFound();
    }

    /**
     * Test if can't detach another user space tags.
     *
     * @return void
     */
    public function test_if_cant_detach_another_user_space_tags(): void
    {
        $this->deleteJson(route('space.detach.tags', $this->createDummySpace()->id), $this->getValidDetachTagsPayload())
            ->assertForbidden()
            ->assertSee('O espa\u00e7o n\u00e3o pertence ao seu us\u00e1rio. Nenhuma opera\u00e7\u00e3o pode ser realizada.');
    }

    /**
     * Test if can't detach space tags without payload.
     *
     * @return void
     */
    public function test_if_cant_detach_space_tags_without_payload(): void
    {
        $this->deleteJson(route('space.detach.tags', $this->space->id))
            ->assertUnprocessable()
            ->assertSee('O campo tags \u00e9 obrigat\u00f3rio.');
    }

    /**
     * Test if can detach space tags with correctly payload.
     *
     * @return void
     */
    public function test_if_can_detach_space_tags_with_correctly_payload(): void
    {
        $this->deleteJson(route('space.detach.tags', $this->space->id), $this->getValidDetachTagsPayload())->assertOk();
    }

    /**
     * Test if can detach space tags in database.
     *
     * @return void
     */
    public function test_if_can_detach_space_tags_in_database(): void
    {
        foreach ($this->getValidDetachTagsPayload() as $tagId) {
            $this->assertDatabaseHas('taggable_tags', [
                'taggable_type' => $this->space::class,
                'taggable_id' => $this->space->id,
                'tag_id' => $tagId
            ]);
        }

        $this->deleteJson(route('space.detach.tags', $this->space->id), $this->getValidDetachTagsPayload())->assertOk();

        foreach ($this->getValidDetachTagsPayload() as $tagId) {
            $this->assertDatabaseMissing('taggable_tags', [
                'taggable_type' => $this->space::class,
                'taggable_id' => $this->space->id,
                'tag_id' => $tagId
            ]);
        }
    }
}
