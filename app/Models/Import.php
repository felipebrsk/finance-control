<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Contracts\Eloquent\ShouldBelongsToSpaceInterface;
use Illuminate\Database\Eloquent\Relations\{BelongsTo, HasMany};

class Import extends Model implements ShouldBelongsToSpaceInterface
{
    /**
     * The attributes that are mass assignable.
     * 
     * @var array<string, string>
     */
    protected $fillable = [
        'name',
        'file',
        'when_column',
        'amount_column',
        'description_column',
        'space_id',
    ];

    /**
     * The attributes that should be date.
     * 
     * @var array<string>
     */
    protected $dates = [
        'end_date',
    ];

    use HasFactory;

    /**
     * Get the space that owns the Import
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function space(): BelongsTo
    {
        return $this->belongsTo(Space::class);
    }

    /**
     * Get all of the spendings for the Import
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function spendings(): HasMany
    {
        return $this->hasMany(Spending::class);
    }
}
