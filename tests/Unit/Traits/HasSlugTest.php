<?php

namespace Tests\Unit\Traits;

use Tests\TestCase;
use Illuminate\Support\Str;
use Tests\Traits\HasDummyCategory;

class HasSlugTest extends TestCase
{
    use HasDummyCategory;

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
     * Test if can create a model with slug automatically.
     *
     * @return void
     */
    public function test_if_can_create_a_model_with_slug_automatically(): void
    {
        $category = $this->createDummyCategory();

        $this->assertNotNull($category->slug);
    }

    /**
     * Test if can create the slug correctly with name column.
     *
     * @return void
     */
    public function test_if_can_create_the_slug_correctly_with_name_column(): void
    {
        $category = $this->createDummyCategory();

        $this->assertTrue(
            $category->slug === Str::slug($category->name),
        );
    }

    /**
     * Test if can auto increment num after repeated slug created.
     *
     * @return void
     */
    public function test_if_can_auto_increment_number_after_repeated_slug_created(): void
    {
        $category = $this->createDummyCategory([
            'name' => 'dummy'
        ]);

        $this->assertTrue(
            $category->slug === Str::slug($category->name),
        );

        for ($i = 1; $i <= 4; $i++) {
            $category = $this->createDummyCategory([
                'name' => 'dummy'
            ]);

            $this->assertTrue(
                $category->slug === Str::slug($category->name) . "-{$i}",
            );
        }
    }
}
