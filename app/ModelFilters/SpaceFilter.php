<?php

namespace App\ModelFilters;

class SpaceFilter extends BaseFilter
{
    /**
     * Filter by name.
     *
     * @param string $name
     * @return void
     */
    public function space(string $name): void
    {
        $this->where('name', 'LIKE', "%{$name}%");
    }

    /**
     * Filter by currency.
     *
     * @param mixed $currencyId
     * @return void
     */
    public function currency(mixed $currencyId): void
    {
        $this->whereCurrencyId($currencyId);
    }
}
