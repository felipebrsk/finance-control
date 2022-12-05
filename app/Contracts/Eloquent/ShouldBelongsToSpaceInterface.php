<?php

namespace App\Contracts\Eloquent;

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
