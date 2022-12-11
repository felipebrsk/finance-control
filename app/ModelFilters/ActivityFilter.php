<?php

namespace App\ModelFilters;

class ActivityFilter extends BaseFilter
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
}
