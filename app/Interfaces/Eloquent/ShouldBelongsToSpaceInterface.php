<?php

namespace App\Interfaces\Eloquent;

use Illuminate\Database\Eloquent\Relations\BelongsTo;

interface ShouldBelongsToSpaceInterface
{
    /**
     * Should belongs to space.
     * 
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function space(): BelongsTo;
}
