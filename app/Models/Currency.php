<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Currency extends Model
{
    /**
     * The attributes that are mass assignable.
     * 
     * @var array<string, string>
     */
    protected $fillable = [
        'name',
        'iso',
        'symbol',
    ];

    use HasFactory;

    /**
     * Get all of the spaces for the Currency
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function spaces(): HasMany
    {
        return $this->hasMany(Space::class);
    }
}
