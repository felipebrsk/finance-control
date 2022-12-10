<?php

namespace App\Repositories;

use App\Models\Recurring;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\{Builder, Collection};
use App\Contracts\Repositories\RecurringRepositoryInterface;
use App\Exceptions\Recurring\RecurringDoesntBelongsToUserSpaceException;

class RecurringRepository extends AbstractRepository implements RecurringRepositoryInterface
{
    /**
     * The recurring model.
     * 
     * @var \App\Models\Recurring
     */
    protected $model = Recurring::class;

    /**
     * Get all recurrings with filter.
     * 
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Pagination\LengthAwarePaginator
     */
    public function allWithFilter(Request $request): LengthAwarePaginator
    {
        return $this->model::fromUserSpaces()->filter($request->all())->paginate(self::PER_PAGE);
    }

    /**
     * Find or fail a recurring.
     * 
     * @param mixed $id
     * @return \App\Models\Recurring
     */
    public function findOrFail(mixed $id): Recurring
    {
        $recurring = $this->model::findOrFail($id);

        if (Auth::user()->cant('view', $recurring)) {
            throw new RecurringDoesntBelongsToUserSpaceException();
        }

        return $recurring;
    }

    /**
     * Create a new recurring.
     * 
     * @param array $data
     * @return \App\Models\Recurring
     */
    public function create(array $data): Recurring
    {
        $recurring = $this->model::create($data);

        if ($tags = issetGetter('tags', $data)) {
            foreach ($tags as $tag) {
                $recurring->tags()->syncWithoutDetaching($tag);
            }
        }

        return $recurring;
    }

    /**
     * Update a recurring.
     * 
     * @param array $data
     * @param mixed $id
     * @return \App\Models\Recurring
     */
    public function update(array $data, mixed $id): Recurring
    {
        $recurring = $this->findOrFail($id);

        if (Auth::user()->cant('update', $recurring)) {
            throw new RecurringDoesntBelongsToUserSpaceException();
        }

        if ($tags = issetGetter('tags', $data)) {
            foreach ($tags as $tag) {
                $recurring->tags()->syncWithoutDetaching($tag);
            }
        }

        $recurring->update($data);

        return $recurring;
    }

    /**
     * Delete a recurring.
     * 
     * @param mixed $id
     * @return void
     */
    public function delete(mixed $id): void
    {
        $recurring = $this->findOrFail($id);

        if (Auth::user()->cant('delete', $recurring)) {
            throw new RecurringDoesntBelongsToUserSpaceException();
        }

        $recurring->delete();
    }

    /**
     * Get the yearly recurrings.
     * 
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getDueYearly(): Collection
    {
        $dateToday = Carbon::today()->toDateString();
        $dateYearAgo = Carbon::today()->subYear()->toDateString();

        return $this->model::where('interval', 'yearly')
            ->where('start_date', '<=', $dateToday)
            ->where(function (Builder $query) use ($dateToday) {
                $query->where('end_date', '>=', $dateToday)->orWhereNull('end_date');
            })->where(function (Builder $query) use ($dateYearAgo) {
                $query->where('last_used_date', '<=', $dateYearAgo)->orWhereNull('last_used_date');
            })->get();
    }

    /**
     * Get the monthly recurrings.
     * 
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getDueMonthly(): Collection
    {
        $today = Carbon::today()->toDateString();
        $lastDateCurrentMonth = Carbon::now()->lastOfMonth()->toDateString();

        $todaySubMonth = Carbon::now()->subMonth()->toDateString();
        $lastDateLastMonth = Carbon::now()->subMonth()->lastOfMonth()->toDateString();

        $query = $this->model::where('interval', 'monthly')
            ->where('start_date', '<=', $today)
            ->where(function (Builder $query) use ($today) {
                $query->where('end_date', '>=', $today)->orWhereNull('end_date');
            });

        if ($today === $lastDateCurrentMonth) {
            $query->where(function (Builder $query) use ($lastDateLastMonth) {
                $query->where('last_used_date', '<=', $lastDateLastMonth)->orWhereNull('last_used_date');
            });
        } else {
            $query->where(function (Builder $query) use ($todaySubMonth) {
                $query->where('last_used_date', '<=', $todaySubMonth)->orWhereNull('last_used_date');
            });
        }

        return $query->get();
    }

    /**
     * Get the biweekly recurrings.
     * 
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getDueBiweekly(): Collection
    {
        $today = Carbon::today()->toDateString();
        $dateTwoWeeksAgo = Carbon::today()->subWeeks(2)->toDateString();

        return $this->model::where('interval', 'biweekly')
            ->where('start_date', '<=', $today)
            ->where(function (Builder $query) use ($today) {
                $query->where('end_date', '>=', $today)->orWhereNull('end_date');
            })->where(function (Builder $query) use ($dateTwoWeeksAgo) {
                $query->where('last_used_date', '<=', $dateTwoWeeksAgo)->orWhereNull('last_used_date');
            })->get();
    }

    /**
     * Get the weekly recurrings.
     * 
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getDueWeekly(): Collection
    {
        $today = Carbon::today()->toDateString();
        $dateWeekAgo = Carbon::today()->subWeek()->toDateString();

        return $this->model::where('interval', 'weekly')
            ->where('start_date', '<=', $today)
            ->where(function ($query) use ($today) {
                $query->where('end_date', '>=', $today)->orWhereNull('end_date');
            })->where(function ($query) use ($dateWeekAgo) {
                $query->where('last_used_date', '<=', $dateWeekAgo)->orWhereNull('last_used_date');
            })->get();
    }

    /**
     * Get the daily recurrings.
     * 
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getDueDaily(): Collection
    {
        $today = Carbon::today()->toDateString();

        return $this->model::where('interval', 'daily')
            ->where('start_date', '<=', $today)
            ->where(function (Builder $query) use ($today) {
                $query->where('end_date', '>=', $today)->orWhereNull('end_date');
            })->where(function (Builder $query) use ($today) {
                $query->where('last_used_date', '<', $today)->orWhereNull('last_used_date');
            })->get();
    }

    /**
     * Detach a recurring tags.
     * 
     * @param array $ids
     * @param mixed $id
     * @return \App\Models\Recurring
     */
    public function detachTags(array $ids, mixed $id): Recurring
    {
        $recurring = $this->model::findOrFail($id);

        if (Auth::user()->cant('update', $recurring)) {
            throw new RecurringDoesntBelongsToUserSpaceException();
        }

        $recurring->tags()->detach($ids);

        return $recurring;
    }

    /**
     * Update from process recurrings job.
     * 
     * @param array $data
     * @param mixed $id
     * @return \App\Models\Recurring
     */
    public function updateFromJob(array $data, mixed $id): Recurring
    {
        $recurring = $this->model::findOrFail($id);

        $recurring->update($data);

        return $recurring;
    }
}
