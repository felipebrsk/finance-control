<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Support\Carbon;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use App\Exceptions\Recurring\UnknownRecurringTypeException;
use Illuminate\Queue\{
    SerializesModels,
    InteractsWithQueue
};
use App\Contracts\Services\{
    RecurringServiceInterface,
    CurrencyServiceInterface,
    EarningServiceInterface,
    SpendingServiceInterface
};

class ProcessRecurringsJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Execute the job.
     *
     * @param \App\Contracts\Services\RecurringServiceInterface $recurringServiceInterface
     * @return void
     */
    public function handle(
        RecurringServiceInterface $recurringServiceInterface,
        CurrencyServiceInterface $currencyServiceInterface,
        EarningServiceInterface $earningServiceInterface,
        SpendingServiceInterface $spendingServiceInterface
    ) {
        foreach ($recurringServiceInterface->getAllDueRecurrings() as $k => $recurring) {
            if (!in_array($recurring->type, ['spending', 'earning'])) {
                throw new UnknownRecurringTypeException($recurring->type);
            }

            $amount = $recurring->amount;

            if ($recurring->currency && $recurring->currency->id !== $recurring->space->currency->id) {
                $amount = $currencyServiceInterface->convert(
                    $recurring->currency->iso,
                    $recurring->space->currency->iso,
                    $amount
                );
            }

            $occurancesDates = [];

            $startingDate = ($recurring->last_used_date ?: $recurring->start_date)->toDateString();
            $today = Carbon::today()->toDateString();

            $cursorDate = $startingDate;

            while ($cursorDate <= $today) {
                if (!$recurring->last_used_date || $cursorDate !== $recurring->last_used_date) {
                    $occurancesDates[$k] = $cursorDate;
                }

                switch ($recurring->interval) {
                    case 'daily':
                        $cursorDate = Carbon::parse($cursorDate)->addDay()->toDateString();
                        break;

                    case 'weekly':
                        $cursorDate = Carbon::parse($cursorDate)->addWeek()->toDateString();
                        break;

                    case 'biweekly':
                        $cursorDate = Carbon::parse($cursorDate)->addWeeks(2)->toDateString();
                        break;

                    case 'yearly':
                        $cursorDate = Carbon::parse($cursorDate)->addYear()->toDateString();
                        break;
                }

                if ($recurring->interval === 'monthly') {
                    $year = Carbon::parse($cursorDate)->year;
                    $month = Carbon::parse($cursorDate)->month;
                    $day = Carbon::parse($startingDate)->day;

                    $month++;

                    if ($month > 12) {
                        $month = 1;
                        $year++;
                    }

                    while (!checkdate($month, $day, $year)) {
                        $day--;
                    }

                    $cursorDate = Carbon::parse(strtotime($year . '-' . $month . '-' . $day))->toDateString();
                }
            }

            foreach ($occurancesDates as $occuranceDate) {
                if ($recurring->type === 'earning') {
                    $earningServiceInterface->create([
                        'description' => $recurring->description,
                        'amount' => $amount,
                        'when' => $occuranceDate,
                        'category_id' => $recurring->category?->id,
                        'space_id' => $recurring->space->id,
                        'recurring_id' => $recurring->id,
                    ]);
                } else if ($recurring->type === 'spending') {
                    $spendingServiceInterface->create([
                        'description' => $recurring->description,
                        'amount' => $amount,
                        'when' => $occuranceDate,
                        'category_id' => $recurring->category?->id,
                        'space_id' => $recurring->space->id,
                        'recurring_id' => $recurring->id,
                    ]);
                }

                $recurringServiceInterface->update([
                    'last_used_date' => $occuranceDate
                ], $recurring->id);
            }
        }
    }
}
