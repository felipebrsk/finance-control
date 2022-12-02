<?php

namespace Tests\Traits;

use App\Models\Category;
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
}