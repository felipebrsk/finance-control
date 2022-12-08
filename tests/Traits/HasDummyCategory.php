<?php

namespace Tests\Traits;

use App\Models\{Category, Space};
use Illuminate\Database\Eloquent\Collection;

trait HasDummyCategory
{
    /**
     * Create dummy category.
     * 
     * @param array $data
     * @return \App\Models\Category
     */
    public function createDummyCategory(array $data = []): Category
    {
        return Category::factory()->create($data);
    }

    /**
     * Create dummy categories.
     * 
     * @param int $times
     * @param array $data
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function createDummyCategories(int $times, array $data = []): Collection
    {
        return Category::factory($times)->create($data);
    }

    /**
     * Create dummy category to.
     * 
     * @param \App\Models\Space $space
     * @param array $data
     * @return \App\Models\Spending
     */
    public function createDummyCategoryTo(Space $space, array $data = []): Category
    {
        $category = $this->createDummyCategory($data);

        $category->space()->associate($space)->save();

        return $category;
    }
}