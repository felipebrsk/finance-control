<?php

namespace Tests\Unit\Requests\Space;

use Tests\TestCase;
use App\Http\Requests\Space\SpaceStoreRequest;
use Tests\Traits\{TestUnitRequests, HasDummyCategory};

class DetachSpaceTagsRequestTest extends TestCase
{
    use HasDummyCategory;
    use TestUnitRequests;

    /**
     * The request to be tested.
     *
     * @return string
     */
    protected function request(): string
    {
        return SpaceStoreRequest::class;
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
