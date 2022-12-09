<?php

namespace App\Models;

use App\Traits\HasSlug;
use App\Helpers\SlugOptions;
use EloquentFilter\Filterable;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\{Builder, Model, SoftDeletes};
use Illuminate\Database\Eloquent\Relations\{BelongsTo, HasMany, MorphToMany};
use Illuminate\Support\Facades\DB;

class Space extends Model
{
    /**
     * The attributes that are mass assignable.
     * 
     * @var array<string, string>
     */
    protected $fillable = [
        'name',
        'slug',
        'user_id',
        'currency_id',
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
     * Get all of the activities for the Space
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function activities(): HasMany
    {
        return $this->hasMany(Activity::class);
    }

    /**
     * Get the currency that owns the Space
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function currency(): BelongsTo
    {
        return $this->belongsTo(Currency::class);
    }

    /**
     * Get all of the tags for the Space
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphToMany
     */
    public function tags(): MorphToMany
    {
        return $this->morphToMany(Tag::class, 'taggable', 'taggable_tags');
    }

    /**
     * Get all of the categories for the Space
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function categories(): HasMany
    {
        return $this->hasMany(Category::class);
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
     * Get the user that owns the Space
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Delete the space with attachments.
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
     * Create scope to auth user.
     * 
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeAuthUserSpaces(Builder $query): Builder
    {
        return $query->whereUserId(Auth::id());
    }

    /**
     * Get the monthly recurrings.
     * 
     * @param int $year
     * @param int $month
     * @return int
     */
    public function getMonthlyEarningRecurrings(int $year = null, int $month = null): int
    {
        $year = getYear($year);
        $month = getMonth($month);

        return $this->recurrings()->whereType('earning')
            ->whereYear('start_date', '<=', $year)
            ->whereMonth('start_date', '<=', $month)
            ->where(function (Builder $query) use ($year, $month) {
                $query->where(function (Builder $query) use ($year, $month) {
                    $query->whereYear('end_date', '>=', $year)->whereMonth('end_date', '>=', $month);
                })->orWhereNull('end_date');
            })->sum('amount');
    }

    /**
     * Get the monthly balance.
     * 
     * @param int $year
     * @param int $month
     * @return int
     */
    public function getMonthlyBalance(int $year = null, int $month = null): int
    {
        $year = getYear($year);
        $month = getMonth($month);

        $earnings = $this->earnings()->whereYear('when', $year)->whereMonth('when', $month);
        $spendings = $this->spendings()->whereYear('when', $year)->whereMonth('when', $month);

        return $spendings->doesntExist() ?
            $earnings->sum('amount') :
            $earnings->sum('amount') - $spendings->sum('amount');
    }

    /**
     * Get the monthly recurrings.
     * 
     * @param int $year
     * @param int $month
     * @return int
     */
    public function getMonthlySpendingRecurrings(int $year = null, int $month = null): int
    {
        $year = getYear($year);
        $month = getMonth($month);

        return $this->recurrings()->whereType('spending')
            ->whereYear('start_date', '<=', $year)
            ->whereMonth('start_date', '<=', $month)
            ->where(function (Builder $query) use ($year, $month) {
                $query->where(function (Builder $query) use ($year, $month) {
                    $query->whereYear('end_date', '>=', $year)->whereMonth('end_date', '>=', $month);
                })->orWhereNull('end_date');
            })->sum('amount');
    }

    /**
     * Get the monthly recurrings.
     * 
     * @param int $year
     * @param int $month
     * @return int
     */
    public function calculateMonthlyRecurrings(int $year = null, int $month = null): int
    {
        $year = getYear($year);
        $month = getMonth($month);

        return ($this->getMonthlyEarningRecurrings($year, $month) - $this->getMonthlySpendingRecurrings($year, $month));
    }
}
