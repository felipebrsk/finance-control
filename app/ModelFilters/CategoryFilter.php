<?php 

namespace App\ModelFilters;

class CategoryFilter extends BaseFilter
{
    /**
     * Filter by space.
     * 
     * @param mixed $spaceId
     * @return void
     */
    public function space(mixed $spaceId): void
    {
        $this->whereSpaceId($spaceId);
    }

    /**
     * Filter by name.
     * 
     * @param string $name
     * @return void
     */
    public function name(string $name): void
    {
        $this->where('name', 'LIKE', "%{$name}%");
    }
}
