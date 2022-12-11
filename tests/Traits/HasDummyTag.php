<?php

namespace Tests\Traits;

use App\Models\{Tag, User};
use Illuminate\Database\Eloquent\Collection;

trait HasDummyTag
{
    /**
     * Create dummy tag.
     *
     * @param array $data
     * @return \App\Models\Tag
     */
    public function createDummyTag(array $data = []): Tag
    {
        return Tag::factory()->create($data);
    }

    /**
     * Create dummy tags.
     *
     * @param int $times
     * @param array $data
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function createDummyTags(int $times, array $data = []): Collection
    {
        return Tag::factory($times)->create($data);
    }

    /**
     * Create dummy tag to.
     *
     * @param \App\Models\User $user
     * @param array $data
     * @return \App\Models\Tag
     */
    public function createDummyTagTo(User $user, array $data = []): Tag
    {
        $tag = $this->createDummyTag($data);

        $tag->user()->associate($user)->save();

        return $tag;
    }
}
