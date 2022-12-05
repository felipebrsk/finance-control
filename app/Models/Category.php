<?php

namespace App\Models;

use App\Traits\HasSlug;
use App\Helpers\SlugOptions;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\{Model, SoftDeletes};
use App\Contracts\Eloquent\ShouldBelongsToSpaceInterface;
use App\Events\Category\{CategoryCreated, CategoryDeleted};
use Illuminate\Database\Eloquent\Relations\{BelongsTo, HasMany};

class Category extends Model implements ShouldBelongsToSpaceInterface
{
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

    use HasFactory;
    use SoftDeletes;
    use HasSlug;

    /**
     * Create model dispatchable events.
     * 
     * @var array<string, string>
     */
    protected $dispatchesEvents = [
        'created' => CategoryCreated::class,
        'deleted' => CategoryDeleted::class
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
