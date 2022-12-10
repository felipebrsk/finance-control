<?php

namespace Tests\Feature\Http\Tags;

use Tests\TestCase;
use Illuminate\Support\Str;
use Tests\Traits\{HasDummyTag, HasDummyUser};

class TagStoreTest extends TestCase
{
    use HasDummyTag;
    use HasDummyUser;

    /**
     * The dummy user.
     * 
     * @var \App\Models\User
     */
    private $user;

    /**
     * Setup new test environments.
     * 
     * @return void
     */
    public function setUp(): void
    {
        parent::setUp();

        $this->user = $this->actingAsDummyUser();
    }

    /**
     * Get valid tag payload.
     * 
     * @return array
     */
    protected function getValidTagPayload(): array
    {
        return [
            'name' => fake()->name(),
            'color' => fake()->colorName(),
        ];
    }

    /**
     * Test if can't create a tag without payload.
     * 
     * @return void
     */
    public function test_if_cant_create_a_tag_without_payload(): void
    {
        $this->postJson(route('tags.store'))
            ->assertUnprocessable()
            ->assertSee('The name field is required.');
    }

    /**
     * Test if can create a tag with correctly payload.
     * 
     * @return void
     */
    public function test_if_can_create_a_tag_with_correctly_payload(): void
    {
        $this->postJson(route('tags.store'), $this->getValidTagPayload())->assertCreated();
    }

    /**
     * Test if can save correctly tag in database.
     * 
     * @return void
     */
    public function test_if_can_save_correctly_tag_in_database(): void
    {
        $this->postJson(route('tags.store'), $data = $this->getValidTagPayload())->assertCreated();

        $this->assertDatabaseHas('tags', [
            'name' => $data['name'],
            'slug' => Str::slug($data['name']),
            'color' => $data['color'],
        ]);
    }

    /**
     * Test if can get correctly tag json structure.
     * 
     * @return void
     */
    public function test_if_can_get_correctly_tag_json_structure(): void
    {
        $this->postJson(route('tags.store'), $this->getValidTagPayload())->assertCreated()->assertJsonStructure([
            'data' => [
                'id',
                'name',
                'slug',
                'color',
                'created_at',
                'updated_at',
            ],
        ]);
    }

    /**
     * Test if can retrieve correctly json tag.
     * 
     * @return void
     */
    public function test_if_can_retrieve_correctly_json_tag(): void
    {
        $this->postJson(route('tags.store'), $data = $this->getValidTagPayload())->assertCreated()->assertJson([
            'data' => [
                'name' => $data['name'],
                'slug' => Str::slug($data['name']),
                'color' => $data['color'],
            ],
        ]);
    }
}
