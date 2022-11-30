<?php

namespace App\Models;

use App\Traits\HasSlug;
use App\Helpers\SlugOptions;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\{Model, SoftDeletes};
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Tag extends Model
{
    /**
     * The attributes that are mass assignable.
     * 
     * @var array<string, string>
     */
    protected $fillable = [
        'name',
        'color',
        'taggable_id',
        'taggable_type',
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
     * Get the morph taggable.
     * 
     * @return \Illuminate\Database\Eloquent\Relations\MorphTo
     */
    public function taggable(): MorphTo
    {
        return $this->morphTo();
    }
}
