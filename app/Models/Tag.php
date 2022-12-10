<?php

namespace App\Models;

use App\Traits\HasSlug;
use App\Helpers\SlugOptions;
use EloquentFilter\Filterable;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\{Builder, Model, SoftDeletes};
use Illuminate\Database\Eloquent\Relations\{BelongsTo, MorphToMany};

class Tag extends Model
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
        'user_id',
    ];

    /**
     * The attributes that should be date.
     * 
     * @var array<string>
     */
    protected $dates = [
        'deleted_at',
    ];

    use HasFactory;
    use SoftDeletes;
    use HasSlug;
    use Filterable;

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
     * Get the user that owns the Tag
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get all of the tags for the Spending
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphMany
     */
    public function taggables(): MorphToMany
    {
        return $this->morphToMany(Tag::class, 'taggable', 'taggable_tags')->using(TaggableTag::class);
    }

    /**
     * Create scope from user.
     * 
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeFromAuthUser(Builder $query): Builder
    {
        return $query->whereUserId(Auth::id());
    }
}
