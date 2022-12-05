<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\{Model, SoftDeletes};
use App\Contracts\Eloquent\ShouldBelongsToSpaceInterface;
use Illuminate\Database\Eloquent\Relations\{BelongsTo, MorphMany};
use App\Events\Transaction\{TransactionCreated, TransactionDeleted};

class Spending extends Model implements ShouldBelongsToSpaceInterface
{
    /**
     * The attributes that are mass assignable.
     * 
     * @var array<string, string>
     */
    protected $fillable = [
        'when',
        'amount',
        'description',
        'space_id',
        'import_id',
        'category_id',
        'recurring_id',
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
     * Create model dispatchable events.
     * 
     * @var array<string, string>
     */
    protected $dispatchesEvents = [
        'created' => TransactionCreated::class,
        'deleted' => TransactionDeleted::class
    ];

    /**
     * Get the space that owns the Spending
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function space(): BelongsTo
    {
        return $this->belongsTo(Space::class);
    }

    /**
     * Get the import that owns the Spending
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function import(): BelongsTo
    {
        return $this->belongsTo(Import::class);
    }

    /**
     * Get the recurring that owns the Spending
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function recurring(): BelongsTo
    {
        return $this->belongsTo(Recurring::class);
    }

    /**
     * Get all of the tags for the Spending
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphMany
     */
    public function tags(): MorphMany
    {
        return $this->morphMany(Tag::class, 'taggable');
    }
}
