<?php

namespace App\Traits;

use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Builder;

trait HasScopeFromUserSpace
{
    /**
     * Make scope to get auth user spaces.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeFromUserSpaces(Builder $query): Builder
    {
        return $query->whereIn(
            'space_id',
            Auth::user()->spaces->pluck('id')
        );
    }
}
