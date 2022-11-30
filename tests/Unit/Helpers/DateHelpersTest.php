<?php

namespace Tests\Unit\Helpers;

use Illuminate\Support\Carbon;
use Tests\TestCase;

class DateHelpersTest extends TestCase
{
    /**
     * Setup new test environments.
     * 
     * @return void
     */
    public function setUp(): void
    {
        parent::setUp();
    }

    /**
     * Test if can get the year correctly.
     * 
     * @return void
     */
    public function test_if_can_get_the_year_correctly(): void
    {
        $this->assertTrue(
            getYear() === Carbon::now()->year
        );
    }

    /**
     * Test if can get the given date year.
     * 
     * @return void
     */
    public function test_if_can_get_the_given_date_year(): void
    {
        $this->assertTrue(
            getYear(Carbon::today()->subDecade()->toDateString()) === Carbon::today()->subDecade()->year
        );
    }

    /**
     * Test if can get the month correctly.
     * 
     * @return void
     */
    public function test_if_can_get_the_month_correctly(): void
    {
        $this->assertTrue(
            getMonth() === Carbon::now()->month
        );
    }

    /**
     * Test if can get the given date month.
     * 
     * @return void
     */
    public function test_if_can_get_the_given_date_month(): void
    {
        $this->assertTrue(
            getMonth(Carbon::today()->subDecade()->toDateString()) === Carbon::today()->subDecade()->month
        );
    }
}
