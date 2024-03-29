<?php

namespace App\Models;

use App\Traits\HasSlug;
use App\Helpers\SlugOptions;
use App\Traits\HasScopeFromUserSpace;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\{Model, SoftDeletes};
use App\Contracts\Eloquent\ShouldBelongsToSpaceInterface;
use Illuminate\Database\Eloquent\Relations\{BelongsTo, HasMany};
use App\Events\Category\{CategoryCreated, CategoryDeleted, CategoryUpdated};
use EloquentFilter\Filterable;

class Category extends Model implements ShouldBelongsToSpaceInterface
{
    use HasSlug;
    use Filterable;
    use HasFactory;
    use SoftDeletes;
    use HasScopeFromUserSpace;
    /**
     * The attributes that are mass assignable.
     *
     * @var array<string, string>
     */
    protected $fillable = [
        'name',
        'slug',
        'color',
        'space_id',
    ];

    /**
     * The attributes that should be date.
     *
     * @var array<int, string>
     */
    protected $dates = [
        'deleted_at',
    ];

    /**
     * Create model dispatchable events.
     *
     * @var array<string, string>
     */
    protected $dispatchesEvents = [
        'created' => CategoryCreated::class,
        'deleted' => CategoryDeleted::class,
        'updated' => CategoryUpdated::class,
    ];

    /**
     * Get the options for generating the slug.
     *
     * @return \App\Helpers\SlugOptions
     */
    public function getSlugOptions(): SlugOptions
    {
        return SlugOptions::create();
    }

    /**
     * Get all of the recurrings for the Space
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function recurrings(): HasMany
    {
        return $this->hasMany(Recurring::class);
    }

    /**
     * Get all of the earnings for the Space
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function earnings(): HasMany
    {
        return $this->hasMany(Earning::class);
    }

    /**
     * Get all of the spendings for the Space
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function spendings(): HasMany
    {
        return $this->hasMany(Spending::class);
    }

    /**
     * Get the space that owns the Category
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function space(): BelongsTo
    {
        return $this->belongsTo(Space::class);
    }
}
