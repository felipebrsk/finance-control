<?php

namespace App\ModelFilters;

use Illuminate\Support\Str;
use EloquentFilter\ModelFilter;

abstract class BaseFilter extends ModelFilter
{
    /**
     * The sortable attributes.
     *
     * @var array
     */
    protected $sortable = [];

    /**
     * Setup new class environments.
     *
     * @return void
     */
    public function setup(): void
    {
        $this->blacklistMethod('isSortable');

        $noSort = $this->input('sort', '') === '';

        if ($noSort) {
            $this->orderBy('created_at', 'DESC');
        }
    }

    /**
     * Sort by given column.
     *
     * @param string $column
     * @return void
     */
    public function sort(string $column): void
    {
        if (method_exists($this, $method = 'sortBy' . Str::studly($column))) {
            $this->$method();
        }

        if ($this->isSortable($column)) {
            $dir = strtolower($this->input('dir')) == 'asc' ? 'ASC' : 'DESC';

            $this->orderBy($column, $dir);
        }
    }

    /**
     * Check if column is sortable.
     *
     * @param string $column
     * @return bool
     */
    protected function isSortable(string $column): bool
    {
        return in_array($column, $this->sortable);
    }
}
