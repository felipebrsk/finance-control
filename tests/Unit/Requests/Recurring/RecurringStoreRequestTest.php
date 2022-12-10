<?php

namespace Tests\Unit\Requests\Recurring;

use Tests\TestCase;
use App\Http\Requests\Recurring\RecurringStoreRequest;
use Illuminate\Support\Carbon;
use Tests\Traits\{HasDummyCategory, HasDummySpace, HasDummyUser, TestUnitRequests};

class RecurringStoreRequestTest extends TestCase
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
        return RecurringStoreRequest::class;
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
     * Test type validation rules.
     * 
     * @return void
     */
    public function test_type_validation_rules(): void
    {
        $this->assertFalse($this->validateField('type', ''));
        $this->assertFalse($this->validateField('type', '123'));
        $this->assertFalse($this->validateField('type', 123));

        $this->assertTrue($this->validateField('type', fake()->randomElement(['spending', 'earning'])));
    }

    /**
     * Test interval validation rules.
     * 
     * @return void
     */
    public function test_interval_validation_rules(): void
    {
        $this->assertFalse($this->validateField('interval', ''));
        $this->assertFalse($this->validateField('interval', '123'));
        $this->assertFalse($this->validateField('interval', 123));

        $this->assertTrue($this->validateField('interval', fake()->randomElement(['daily', 'weekly', 'biweekly', 'monthly', 'yearly'])));
    }

    /**
     * Test start_date validation rules.
     * 
     * @return void
     */
    public function test_start_date_validation_rules(): void
    {
        $this->assertFalse($this->validateField('start_date', ''));
        $this->assertFalse($this->validateField('start_date', '123'));
        $this->assertFalse($this->validateField('start_date', 123));
        $this->assertFalse($this->validateField('start_date', Carbon::today()->subMonth()->toDateString()));

        $this->assertTrue($this->validateField('start_date', Carbon::today()->toDateString()));
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
