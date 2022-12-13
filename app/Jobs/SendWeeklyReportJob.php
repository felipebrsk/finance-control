<?php

namespace App\Jobs;

use App\Models\User;
use Illuminate\Bus\Queueable;
use App\Mail\WeeklyReportMail;
use Illuminate\Support\Facades\Mail;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use App\Contracts\Services\UserServiceInterface;
use Illuminate\Support\{Carbon, LazyCollection};
use Illuminate\Queue\{SerializesModels, InteractsWithQueue};

class SendWeeklyReportJob implements ShouldQueue
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle(UserServiceInterface $userServiceInterface): void
    {
        $week = Carbon::today()->format('W');
        $lastWeekDate = Carbon::today()->subWeek()->toDateString();
        $currentDate = Carbon::today()->toDateString();
        LazyCollection::make($userServiceInterface->allToWeeklyReport())->each(function (User $user) use ($week, $lastWeekDate, $currentDate) {
            foreach ($user->spaces as $space) {
                $spendingQuery = $space->spendings()
                    ->whereDate('when', '>=', $lastWeekDate)
                    ->whereDate('when', '<=', $currentDate);

                $totalSpent = $spendingQuery->sum('amount');
                $highestSpent = $spendingQuery->orderByDesc('amount')->first();

                Mail::to($space->user->email)->queue(new WeeklyReportMail($space, $week, $totalSpent, $highestSpent));
            }
        });
    }
}
