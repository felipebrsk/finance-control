<?php

namespace Tests\Feature\Http\Earnings;

use Tests\TestCase;
use Tests\Traits\{
    HasDummyEarning,
    HasDummySpace,
    HasDummyTag,
    HasDummyUser
};

class EarningDetachTagsTest extends TestCase
{
    use HasDummyTag;
    use HasDummyUser;
    use HasDummySpace;
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
     * The dummy earning.
     *
     * @var \App\Models\Earning
     */
    private $earning;

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
        $this->earning = $this->createDummyEarningTo($this->space);
        $this->tags = $this->createDummyTags(4, [
            'user_id' => $this->user->id,
        ]);
        $this->earning->tags()->saveMany($this->tags);
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
     * Test if can throw 404 if earning doesn't exists.
     *
     * @return void
     */
    public function test_if_can_throw_not_found_if_earning_doesnt_exists(): void
    {
        $this->deleteJson(route('earnings.detach.tags', 99999999), $this->getValidDetachTagsPayload())->assertNotFound();
    }

    /**
     * Test if can't detach another user earning tags.
     *
     * @return void
     */
    public function test_if_cant_detach_another_user_earning_tags(): void
    {
        $this->deleteJson(route('earnings.detach.tags', $this->createDummyEarning()->id), $this->getValidDetachTagsPayload())
            ->assertForbidden()
            ->assertSee('Esta conta n\u00e3o pertence \u00e0 nenhum dos seus espa\u00e7os. Nenhuma opera\u00e7\u00e3o pode ser realizada.');
    }

    /**
     * Test if can't detach earning tags without payload.
     *
     * @return void
     */
    public function test_if_cant_detach_earning_tags_without_payload(): void
    {
        $this->deleteJson(route('earnings.detach.tags', $this->earning->id))
            ->assertUnprocessable()
            ->assertSee('The tags field is required.');
    }

    /**
     * Test if can detach earning tags with correctly payload.
     *
     * @return void
     */
    public function test_if_can_detach_earning_tags_with_correctly_payload(): void
    {
        $this->deleteJson(route('earnings.detach.tags', $this->earning->id), $this->getValidDetachTagsPayload())->assertOk();
    }

    /**
     * Test if can detach earning tags in database.
     *
     * @return void
     */
    public function test_if_can_detach_earning_tags_in_database(): void
    {
        foreach ($this->getValidDetachTagsPayload() as $tagId) {
            $this->assertDatabaseHas('taggable_tags', [
                'taggable_type' => $this->earning::class,
                'taggable_id' => $this->earning->id,
                'tag_id' => $tagId
            ]);
        }

        $this->deleteJson(route('earnings.detach.tags', $this->earning->id), $this->getValidDetachTagsPayload())->assertOk();

        foreach ($this->getValidDetachTagsPayload() as $tagId) {
            $this->assertDatabaseMissing('taggable_tags', [
                'taggable_type' => $this->earning::class,
                'taggable_id' => $this->earning->id,
                'tag_id' => $tagId
            ]);
        }
    }
}
