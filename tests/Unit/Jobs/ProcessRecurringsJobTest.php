<?php

namespace Tests\Unit\Jobs;

use Tests\TestCase;
use App\Models\Currency;
use GuzzleHttp\Client;
use Illuminate\Support\Carbon;
use App\Jobs\ProcessRecurringsJob;
use Symfony\Component\DomCrawler\Crawler;
use Illuminate\Support\Facades\{Bus, Queue};
use Tests\Traits\{HasDummyCategory, HasDummyRecurring, HasDummySpace, HasDummyUser};

class ProcessRecurringsJobTest extends TestCase
{
    use HasDummyUser;
    use HasDummySpace;
    use HasDummyCategory;
    use HasDummyRecurring;

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

        $this->user = $this->actingAsDummyUser();
        $this->space = $this->createDummySpaceTo($this->user, [
            'currency_id' => Currency::whereIso('BRL')->value('id'),
        ]);
    }

    /**
     * Test if can dispatch the job.
     * 
     * @return void
     */
    public function test_if_can_dispatch_process_recurrings_job(): void
    {
        Bus::fake();

        Bus::dispatch(new ProcessRecurringsJob);

        Bus::assertDispatched(ProcessRecurringsJob::class, 1);
    }

    /**
     * Test if can queue process recurrings job.
     * 
     * @return void
     */
    public function test_if_can_queue_process_recurrings_job(): void
    {
        Queue::fake();

        Bus::dispatch(new ProcessRecurringsJob);

        Queue::assertPushed(ProcessRecurringsJob::class, 1);
    }

    /**
     * Test if can process the spending recurrings.
     * 
     * @return void
     */
    public function test_if_can_process_the_spending_recurrings(): void
    {
        # assert there is not spendings generated til now.
        $this->assertDatabaseCount('spendings', 0)
            ->assertDatabaseMissing('spendings', [
                'space_id' => $this->space->id,
            ]);

        $recurring = $this->createDummyRecurring([
            'description' => 'Amazon Prime Video',
            'amount' => 990,
            'type' => 'spending',
            'interval' => 'monthly',
            'start_date' => Carbon::yesterday(),
            'space_id' => $this->space->id,
            'currency_id' => Currency::whereIso('BRL')->value('id'),
            'category_id' => $this->createDummyCategory([
                'space_id' => $this->space->id,
            ])->id,
        ]);

        # dispatch the job to process recurrings.
        ProcessRecurringsJob::dispatch();

        # assert job generated new spending record.
        $this->assertDatabaseCount('spendings', 1)
            ->assertDatabaseHas('spendings', [
                'space_id' => $recurring->space->id,
                'category_id' => $recurring->category->id,
                'amount' => $recurring->amount,
                'description' => $recurring->description,
                'recurring_id' => $recurring->id,
            ]);
    }

    /**
     * Test if can process the earning recurrings.
     * 
     * @return void
     */
    public function test_if_can_process_the_earning_recurrings(): void
    {
        # assert there is not earnings generated til now.
        $this->assertDatabaseCount('earnings', 1) # seeds
            ->assertDatabaseMissing('earnings', [
                'space_id' => $this->space->id,
            ]);

        $recurring = $this->createDummyRecurring([
            'description' => 'Wage',
            'amount' => 200000,
            'type' => 'earning',
            'interval' => 'monthly',
            'start_date' => Carbon::yesterday(),
            'space_id' => $this->space->id,
            'currency_id' => Currency::whereIso('BRL')->value('id'),
        ]);

        # dispatch the job to process recurrings.
        ProcessRecurringsJob::dispatch();

        # assert job generated new earning record.
        $this->assertDatabaseCount('earnings', 2)
            ->assertDatabaseHas('earnings', [
                'space_id' => $recurring->space->id,
                'category_id' => $recurring->category->id,
                'amount' => $recurring->amount,
                'description' => $recurring->description,
                'recurring_id' => $recurring->id,
            ]);
    }

    /**
     * Test if can convert the currency of recurring if different from space and generate spending with converted amount.
     * 
     * @return void
     */
    public function test_if_can_convert_the_currency_of_recurring_if_is_different_from_space_and_generate_spending_with_converted_amount(): void
    {
        $recurring = $this->createDummyRecurring([
            'description' => 'Wage',
            'amount' => 500,
            'type' => 'spending',
            'interval' => 'monthly',
            'start_date' => Carbon::yesterday(),
            'space_id' => $this->space->id,
            'currency_id' => Currency::whereIso('USD')->value('id'),
            'category_id' => $this->createDummyCategory([
                'space_id' => $this->space->id,
            ])->id,
        ]);

        $amount = $recurring->amount;

        ProcessRecurringsJob::dispatch();

        $response = (new Client)->get("https://www.valutafx.com/{$recurring->currency->iso}-{$this->space->currency->iso}.htm");

        $result = (new Crawler($response->getBody()->__toString()))->filter('.converter-result > .rate-value')->first()->text();

        $rate = str_replace(',', '', $result);

        $amount = (int)round($amount * $rate);

        $this->assertDatabaseCount('spendings', 1)
            ->assertDatabaseHas('spendings', [
                'space_id' => $recurring->space->id,
                'category_id' => $recurring->category->id,
                'amount' => $amount,
                'description' => $recurring->description,
                'recurring_id' => $recurring->id,
            ]);
    }

    /**
     * Test if can fill the when date on generated earning on recurring process job.
     * 
     * @return void
     */
    public function test_if_can_fill_the_when_date_on_generated_earning_on_recurring_process_job(): void
    {
        $recurring = $this->createDummyRecurring([
            'description' => 'Wage',
            'amount' => 200000,
            'type' => 'earning',
            'interval' => 'monthly',
            'start_date' => Carbon::yesterday(),
            'space_id' => $this->space->id,
            'currency_id' => Currency::whereIso('BRL')->value('id'),
        ]);

        ProcessRecurringsJob::dispatch();

        $this->assertDatabaseCount('earnings', 2)
            ->assertDatabaseHas('earnings', [
                'when' => $recurring->start_date->toDateString(),
            ]);
    }

    /**
     * Test if can fill the recurring last used date on recurring process job.
     * 
     * @return void
     */
    public function test_if_can_fill_the_recurring_last_used_date_on_recurring_process_job(): void
    {
        $recurring = $this->createDummyRecurring([
            'description' => 'Wage',
            'amount' => 200000,
            'type' => 'earning',
            'interval' => 'monthly',
            'start_date' => Carbon::yesterday(),
            'space_id' => $this->space->id,
            'currency_id' => Currency::whereIso('BRL')->value('id'),
        ]);

        $this->assertDatabaseHas('recurrings', [
            'id' => $recurring->id,
            'last_used_date' => null,
        ]);

        ProcessRecurringsJob::dispatch();

        $this->assertDatabaseHas('recurrings', [
            'id' => $recurring->id,
            'last_used_date' => $recurring->start_date->toDateString(),
        ]);
    }
}
