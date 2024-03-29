<?php

namespace App\Models;

use EloquentFilter\Filterable;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use App\Traits\HasScopeFromUserSpace;
use Illuminate\Database\Eloquent\{Model, SoftDeletes};
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Contracts\Eloquent\ShouldBelongsToSpaceInterface;
use Illuminate\Database\Eloquent\Relations\{BelongsTo, HasMany, MorphToMany};
use App\Events\Recurring\{RecurringCreated, RecurringDeleted, RecurringUpdated};

class Recurring extends Model implements ShouldBelongsToSpaceInterface
{
    use HasFactory;
    use SoftDeletes;
    use HasScopeFromUserSpace;
    use Filterable;
    /**
     * The attributes that are mass assignable.
     *
     * @var array<string, string>
     */
    protected $fillable = [
        'type',
        'when',
        'amount',
        'interval',
        'end_date',
        'start_date',
        'description',
        'last_used_date',
        'space_id',
        'currency_id',
        'category_id',
    ];

    /**
     * The attributes that should be date.
     *
     * @var array<string>
     */
    protected $dates = [
        'end_date',
        'deleted_at',
        'start_date',
    ];

    /**
     * Create model dispatchable events.
     *
     * @var array<string, string>
     */
    protected $dispatchesEvents = [
        'created' => RecurringCreated::class,
        'deleted' => RecurringDeleted::class,
        'updated' => RecurringUpdated::class,
    ];

    /**
     * Get the supported recurring intervals.
     *
     * @return array
     */
    public function getSupportedIntervals(): array
    {
        return [
            'yearly',
            'monthly',
            'biweekly',
            'weekly',
            'daily'
        ];
    }

    /**
     * Get the due date attribute.
     *
     * @return int
     */
    public function getDueDaysAttribute(): int
    {
        $today = Carbon::today();
        $todayDay = $today->day;
        $lastMonthDay = $today->lastOfMonth()->day;

        if (
            $this->starts_on <= $today->toDateString() &&
            ($this->ends_on >= $today->toDateString() || !$this->ends_on)
        ) {
            if ($todayDay > $this->day) {
                return $lastMonthDay - $todayDay + $this->day;
            }

            return $this->day - $todayDay;
        }

        return 0;
    }

    /**
     * Get the status attribute.
     *
     * @return int
     */
    public function getStatusAttribute(): bool
    {
        $today = Carbon::today();

        return $this->starts_on <= $today && ($this->ends_on >= $today || !$this->ends_on);
    }

    /**
     * Delete the recurring with attachments.
     *
     * @return bool
     */
    public function delete(): bool
    {
        return DB::transaction(function () {
            $this->tags()->detach();

            return parent::delete();
        });
    }

    /**
     * Get the space that owns the Recurring
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function space(): BelongsTo
    {
        return $this->belongsTo(Space::class);
    }

    /**
     * Get all of the earnings for the Recurring
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function earnings(): HasMany
    {
        return $this->hasMany(Earning::class);
    }

    /**
     * Get all of the spendings for the Recurring
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function spendings(): HasMany
    {
        return $this->hasMany(Spending::class);
    }

    /**
     * Get all of the tags for the Recurring
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphMany
     */
    public function tags(): MorphToMany
    {
        return $this->morphToMany(Tag::class, 'taggable', 'taggable_tags');
    }

    /**
     * Get the currency that owns the Recurring
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function currency(): BelongsTo
    {
        return $this->belongsTo(Currency::class);
    }

    /**
     * Get the category that owns the Recurring
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }
}
