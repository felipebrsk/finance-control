<?php

namespace Tests\Unit\Requests\Category;

use Tests\TestCase;
use Tests\Traits\TestUnitRequests;
use Tests\Traits\{HasDummySpace, HasDummyUser};
use App\Http\Requests\Category\CategoryStoreRequest;

class CategoryStoreRequestTest extends TestCase
{
    use HasDummyUser;
    use HasDummySpace;
    use TestUnitRequests;

    /**
     * The request to be tested.
     *
     * @return string
     */
    protected function request(): string
    {
        return CategoryStoreRequest::class;
    }

    /**
     * Test name validation rules.
     *
     * @return void
     */
    public function test_name_validation_rules(): void
    {
        $this->assertFalse($this->validateField('name', ''));
        $this->assertFalse($this->validateField('name', 123));
        $this->assertTrue($this->validateField('name', fake()->name()));
    }

    /**
     * Test color validation rules.
     *
     * @return void
     */
    public function test_color_validation_rules(): void
    {
        $this->assertFalse($this->validateField('color', 123));
        $this->assertTrue($this->validateField('color', fake()->colorName()));
    }

    /**
     * Test space_id validation rules.
     *
     * @return void
     */
    public function test_space_id_validation_rules(): void
    {
        $this->assertFalse($this->validateField('space_id', ''));
        $this->assertFalse($this->validateField('space_id', '123'));
        $this->assertFalse($this->validateField('space_id', 123));
        $this->assertFalse($this->validateField('space_id', 99999999));

        $this->assertTrue($this->validateField('space_id', $this->createDummySpace()->id));
    }
}
