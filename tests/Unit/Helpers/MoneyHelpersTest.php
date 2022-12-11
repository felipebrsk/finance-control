<?php

namespace Tests\Unit\Helpers;

use Tests\TestCase;

class MoneyHelpersTest extends TestCase
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
     * Test if can format the given amount to given currency.
     *
     * @return void
     */
    public function test_if_can_format_the_given_amount_to_given_currency(): void
    {
        $amount = 10000;

        $brlAmount = formatCurrency($amount, 'BRL');

        $this->assertTrue(
            $amount !== $brlAmount &&
                $brlAmount === 'R$ 100,00'
        );

        $usdAmount = formatCurrency($amount, 'USD');

        $this->assertTrue(
            $amount !== $usdAmount &&
                $usdAmount === 'US$ 100,00'
        );
    }
}
