<?php

namespace Tests\Feature\Http\Spendings;

use Tests\TestCase;
use Tests\Traits\{
    HasDummySpace,
    HasDummySpending,
    HasDummyTag,
    HasDummyUser
};

class SpendingDetachTagsTest extends TestCase
{
    use HasDummyTag;
    use HasDummyUser;
    use HasDummySpace;
    use HasDummySpending;

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
     * The dummy spending.
     * 
     * @var \App\Models\Spending
     */
    private $spending;

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
        $this->spending = $this->createDummySpendingTo($this->space);
        $this->tags = $this->createDummyTags(4, [
            'user_id' => $this->user->id,
        ]);
        $this->spending->tags()->saveMany($this->tags);
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
     * Test if can throw 404 if spending doesn't exists.
     * 
     * @return void
     */
    public function test_if_can_throw_not_found_if_spending_doesnt_exists(): void
    {
        $this->deleteJson(route('spendings.detach.tags', 99999999), $this->getValidDetachTagsPayload())->assertNotFound();
    }

    /**
     * Test if can't detach another user spending tags.
     * 
     * @return void
     */
    public function test_if_cant_detach_another_user_spending_tags(): void
    {
        $this->deleteJson(route('spendings.detach.tags', $this->createDummySpending()->id), $this->getValidDetachTagsPayload())
            ->assertForbidden()
            ->assertSee('Esta conta n\u00e3o pertence \u00e0 nenhum dos seus espa\u00e7os. Nenhuma opera\u00e7\u00e3o pode ser realizada.');
    }

    /**
     * Test if can't detach spending tags without payload.
     * 
     * @return void
     */
    public function test_if_cant_detach_spending_tags_without_payload(): void
    {
        $this->deleteJson(route('spendings.detach.tags', $this->spending->id))
            ->assertUnprocessable()
            ->assertSee('The tags field is required.');
    }

    /**
     * Test if can detach spending tags with correctly payload.
     * 
     * @return void
     */
    public function test_if_can_detach_spending_tags_with_correctly_payload(): void
    {
        $this->deleteJson(route('spendings.detach.tags', $this->spending->id), $this->getValidDetachTagsPayload())->assertOk();
    }

    /**
     * Test if can detach spending tags in database.
     * 
     * @return void
     */
    public function test_if_can_detach_spending_tags_in_database(): void
    {
        foreach ($this->getValidDetachTagsPayload() as $tagId) {
            $this->assertDatabaseHas('taggable_tags', [
                'taggable_type' => $this->spending::class,
                'taggable_id' => $this->spending->id,
                'tag_id' => $tagId
            ]);
        }

        $this->deleteJson(route('spendings.detach.tags', $this->spending->id), $this->getValidDetachTagsPayload())->assertOk();

        foreach ($this->getValidDetachTagsPayload() as $tagId) {
            $this->assertDatabaseMissing('taggable_tags', [
                'taggable_type' => $this->spending::class,
                'taggable_id' => $this->spending->id,
                'tag_id' => $tagId
            ]);
        }
    }
}
