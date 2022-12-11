<?php

namespace Tests\Feature\Http\Recurrings;

;

use Tests\TestCase;
use Tests\Traits\{
    HasDummyRecurring,
    HasDummySpace,
    HasDummyTag,
    HasDummyUser
};

class RecurringDetachTagsTest extends TestCase
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
        $this->recurring = $this->createDummyRecurringTo($this->space);
        $this->recurring->tags()->saveMany($this->tags);
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
     * Test if can throw 404 if recurring doesn't exists.
     *
     * @return void
     */
    public function test_if_can_throw_not_found_if_recurring_doesnt_exists(): void
    {
        $this->deleteJson(route('recurrings.detach.tags', 99999999), $this->getValidDetachTagsPayload())->assertNotFound();
    }

    /**
     * Test if can't detach another user recurring recurring tags.
     *
     * @return void
     */
    public function test_if_cant_detach_another_user_recurring_recurring_tags(): void
    {
        $this->deleteJson(route('recurrings.detach.tags', $this->createDummyRecurring()->id), $this->getValidDetachTagsPayload())
            ->assertForbidden()
            ->assertSee('Esta conta n\u00e3o pertence \u00e0 nenhum dos seus espa\u00e7os. Nenhuma opera\u00e7\u00e3o pode ser realizada.');
    }

    /**
     * Test if can't detach recurring tags without payload.
     *
     * @return void
     */
    public function test_if_cant_detach_recurring_tags_without_payload(): void
    {
        $this->deleteJson(route('recurrings.detach.tags', $this->recurring->id))
            ->assertUnprocessable()
            ->assertSee('O campo tags \u00e9 obrigat\u00f3rio.');
    }

    /**
     * Test if can detach recurring tags with correctly payload.
     *
     * @return void
     */
    public function test_if_can_detach_recurring_tags_with_correctly_payload(): void
    {
        $this->deleteJson(route('recurrings.detach.tags', $this->recurring->id), $this->getValidDetachTagsPayload())->assertOk();
    }

    /**
     * Test if can detach recurring tags in database.
     *
     * @return void
     */
    public function test_if_can_detach_recurring_tags_in_database(): void
    {
        foreach ($this->getValidDetachTagsPayload() as $tagId) {
            $this->assertDatabaseHas('taggable_tags', [
                'taggable_type' => $this->recurring::class,
                'taggable_id' => $this->recurring->id,
                'tag_id' => $tagId
            ]);
        }

        $this->deleteJson(route('recurrings.detach.tags', $this->recurring->id), $this->getValidDetachTagsPayload())->assertOk();

        foreach ($this->getValidDetachTagsPayload() as $tagId) {
            $this->assertDatabaseMissing('taggable_tags', [
                'taggable_type' => $this->recurring::class,
                'taggable_id' => $this->recurring->id,
                'tag_id' => $tagId
            ]);
        }
    }
}
