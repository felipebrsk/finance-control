<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Interfaces\Eloquent\ShouldBelongsToSpaceInterface;
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
}
