<?php

namespace App\Models;

use EloquentFilter\Filterable;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\{Model, Builder};
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Contracts\Eloquent\ShouldBelongsToSpaceInterface;
use Illuminate\Database\Eloquent\Relations\{MorphTo, BelongsTo};

class Activity extends Model implements ShouldBelongsToSpaceInterface
{
    /**
     * The attributes that are mass assignable.
     * 
     * @var array<string, string>
     */
    protected $fillable = [
        'action',
        'activitable_id',
        'activitable_type',
        'space_id',
    ];

    use HasFactory;
    use Filterable;

    /**
     * Get the morph activitable.
     * 
     * @return \Illuminate\Database\Eloquent\Relations\MorphTo
     */
    public function activitable(): MorphTo
    {
        return $this->morphTo();
    }

    /**
     * Get the space that owns the Activity
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function space(): BelongsTo
    {
        return $this->belongsTo(Space::class);
    }

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
