<?php

namespace Tests\Unit\Jobs;

use Tests\TestCase;
use App\Models\{User, Space};
use App\Mail\WeeklyReportMail;
use Illuminate\Support\Carbon;
use App\Jobs\SendWeeklyReportJob;
use Illuminate\Support\Facades\{Bus, DB, Mail, Queue};
use Tests\Traits\{HasDummySpace, HasDummySpending, HasDummyUser};

class SendWeeklyReportJobTest extends TestCase
{
    use HasDummyUser;
    use HasDummySpace;
    use HasDummySpending;

    /**
     * The dummy user.
     *
     * @var \App\Models\User
     */
    private $user;

    /**
     * The dummy user space.
     *
     * @var \App\Models\Space
     */
    private $space;

    /**
     * Setup new test environments.
     *
     * @return void
     */
    public function setUp(): void
    {
        parent::setUp();

        $this->user = $this->createDummyUser(['weekly_report' => true]);
        DB::table('spaces')->truncate();
        $this->space = $this->createDummySpaceTo($this->user);
    }

    /**
     * Test if can dispatch the job.
     *
     * @return void
     */
    public function test_if_can_dispatch_send_weekly_report_job(): void
    {
        Bus::fake();

        Bus::dispatch(new SendWeeklyReportJob());

        Bus::assertDispatched(SendWeeklyReportJob::class, 1);
    }

    /**
     * Test if can queue process recurrings job.
     *
     * @return void
     */
    public function test_if_can_queue_send_weekly_report_job(): void
    {
        Queue::fake();

        Bus::dispatch(new SendWeeklyReportJob());

        Queue::assertPushed(SendWeeklyReportJob::class, 1);
    }

    /**
     * Test if email was sent on job dispatch.
     *
     * @return void
     */
    public function test_if_email_was_sent_on_job_dispatch(): void
    {
        Mail::fake();

        SendWeeklyReportJob::dispatch();

        Mail::assertQueued(WeeklyReportMail::class, User::whereWeeklyReport(true)->count());
    }

    /**
     * Test if can send the email with correctly data.
     *
     * @return void
     */
    public function test_if_can_send_the_email_with_correctly_data(): void
    {
        Mail::fake();

        $lowestSpending = $this->createDummySpending([
            'amount' => 100000,
            'when' => Carbon::today()->subDay()->toDateString(),
            'space_id' => $this->space->id
        ]);

        $highestSpending = $this->createDummySpending([
            'amount' => 200000,
            'when' => Carbon::today()->subDay()->toDateString(),
            'space_id' => $this->space->id
        ]);

        Space::where('id', '!=', $this->space->id)->where('user_id', '!=', $this->user->id)->delete();

        SendWeeklyReportJob::dispatch();

        Mail::assertQueued(WeeklyReportMail::class, function (WeeklyReportMail $mail) use ($lowestSpending, $highestSpending) {
            return $mail->week === (int)Carbon::today()->format('W') &&
                $mail->space->id === $this->space->id &&
                $mail->totalSpent === ($lowestSpending->amount + $highestSpending->amount) &&
                $mail->highestSpent->id === $highestSpending->id;
        });
    }
}
