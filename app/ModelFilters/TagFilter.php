<?php

namespace App\ModelFilters;

class TagFilter extends BaseFilter
{
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

    /**
     * Filter by color.
     * 
     * @param string $color
     * @return void
     */
    public function color(string $color): void
    {
        $this->where('color', 'LIKE', "%{$color}%");
    }
}
