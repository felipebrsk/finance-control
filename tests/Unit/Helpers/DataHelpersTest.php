<?php

namespace Tests\Unit\Helpers;

use Tests\TestCase;

class DataHelpersTest extends TestCase
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
     * Test if can get the given value if is set on array.
     * 
     * @return void
     */
    public function test_if_can_get_the_given_value_if_is_set_on_array(): void
    {
        $testableArray = [
            'name' => $name = fake()->name(),
        ];

        $getName = issetGetter('name', $testableArray);

        $this->assertEquals($getName, $name);
    }

    /**
     * Test if can get null if given value is not set on array.
     * 
     * @return void
     */
    public function test_if_can_get_null_if_given_value_is_not_set_on_array(): void
    {
        $testableArray = [
            'name' => fake()->name(),
        ];

        $getInexistent = issetGetter('inexistent', $testableArray);

        $this->assertEquals($getInexistent, null);
    }
}
