<?php

namespace Tests\Unit\Requests\Spending;

use Tests\TestCase;
use Tests\Traits\TestUnitRequests;
use App\Http\Requests\Spending\SpendingStoreRequest;
use Tests\Traits\{HasDummyCategory, HasDummySpace, HasDummyUser};

class SpendingStoreRequestTest extends TestCase
{
    use HasDummyUser;
    use HasDummySpace;
    use HasDummyCategory;
    use TestUnitRequests;

    /**
     * The request to be tested.
     *
     * @return string
     */
    protected function request(): string
    {
        return SpendingStoreRequest::class;
    }

    /**
     * Test description validation rules.
     *
     * @return void
     */
    public function test_description_validation_rules(): void
    {
        $this->assertFalse($this->validateField('description', ''));
        $this->assertFalse($this->validateField('description', 123));
        $this->assertTrue($this->validateField('description', fake()->text()));
    }

    /**
     * Test amount validation rules.
     *
     * @return void
     */
    public function test_amount_validation_rules(): void
    {
        $this->assertFalse($this->validateField('amount', 0));

        $this->assertTrue($this->validateField('amount', 123));
        $this->assertTrue($this->validateField('amount', fake()->numberBetween(100, 99900)));
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

    /**
     * Test category_id validation rules.
     *
     * @return void
     */
    public function test_category_id_validation_rules(): void
    {
        $this->assertFalse($this->validateField('category_id', '123'));
        $this->assertFalse($this->validateField('category_id', 123));
        $this->assertFalse($this->validateField('category_id', 99999999));

        $this->assertTrue($this->validateField('category_id', ''));
        $this->assertTrue($this->validateField('category_id', $this->createDummyCategory()->id));
    }

    /**
     * Test tags validation rules.
     *
     * @return void
     */
    public function test_tags_validation_rules(): void
    {
        $this->assertFalse($this->validateField('tags', '123'));
        $this->assertFalse($this->validateField('tags', 123));
        $this->assertTrue($this->validateField('tags', []));
    }

    /**
     * Test tag ids validation rules.
     *
     * @return void
     */
    public function test_tag_ids_validation_rules(): void
    {
        $this->assertTrue($this->validateField('tags.*', [
            $this->createDummyCategory()->id,
        ]));
    }
}
