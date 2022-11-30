<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\{Model, SoftDeletes};
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Attachment extends Model
{
    /**
     * The attributes that are mass assignable.
     * 
     * @var array<string, string>
     */
    protected $fillable = [
        'attachment',
        'attachmentable_id',
        'attachmentable_type',
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

    /**
     * Get the morph attachmentable.
     * 
     * @return \Illuminate\Database\Eloquent\Relations\MorphTo
     */
    public function attachmentable(): MorphTo
    {
        return $this->morphTo();
    }
}
