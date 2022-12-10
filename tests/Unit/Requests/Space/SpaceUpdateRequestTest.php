<?php

namespace Tests\Unit\Requests\Space;

use Tests\TestCase;
use Tests\Traits\TestUnitRequests;
use App\Http\Requests\Space\SpaceUpdateRequest;
use App\Models\Currency;
use Tests\Traits\{HasDummyCategory, HasDummyCurrency, HasDummyUser};

class SpaceUpdateRequestTest extends TestCase
{
    use HasDummyUser;
    use HasDummyCurrency;
    use HasDummyCategory;
    use TestUnitRequests;

    /**
     * The request to be tested.
     *
     * @return string
     */
    protected function request(): string
    {
        return SpaceUpdateRequest::class;
    }

    /**
     * Test name validation rules.
     * 
     * @return void
     */
    public function test_name_validation_rules(): void
    {
        $this->assertFalse($this->validateField('name', 123));

        $this->assertTrue($this->validateField('name', ''));
        $this->assertTrue($this->validateField('name', fake()->text()));
    }

    /**
     * Test currency_id validation rules.
     * 
     * @return void
     */
    public function test_currency_id_validation_rules(): void
    {
        $this->assertFalse($this->validateField('currency_id', '123'));
        $this->assertFalse($this->validateField('currency_id', 123));
        $this->assertFalse($this->validateField('currency_id', 99999999));
        
        $this->assertTrue($this->validateField('currency_id', ''));
        $this->assertTrue($this->validateField('currency_id', Currency::whereIso('BRL')->value('id')));
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
