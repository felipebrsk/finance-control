<?php

namespace Tests\Feature\Http\Tags;

use Tests\TestCase;
use Illuminate\Support\Str;
use Tests\Traits\{HasDummyTag, HasDummyUser};

class TagUpdateTest extends TestCase
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
     * The dummy tag.
     * 
     * @var \App\Models\Tag
     */
    private $tag;

    /**
     * Setup new test environments.
     * 
     * @return void
     */
    public function setUp(): void
    {
        parent::setUp();

        $this->user = $this->actingAsDummyUser();
        $this->tag = $this->createDummyTagTo($this->user);
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
     * Test if can throw 404 if tag doesn't exists.
     * 
     * @return void
     */
    public function test_if_can_throw_not_found_if_tag_doesnt_exists(): void
    {
        $this->putJson(route('tags.update', 99999999))->assertNotFound();
    }

    /**
     * Test if can't update another user tag.
     * 
     * @return void
     */
    public function test_if_cant_update_another_user_tag(): void
    {
        $this->putJson(route('tags.update', $this->createDummyTag()->id))
            ->assertForbidden()
            ->assertSee('Uma ou mais tags n\u00e3o pertencem ao seu usu\u00e1rio. Nenhuma opera\u00e7\u00e3o pode ser feita. Tente criar uma nova tag e repetir o processo.');
    }

    /**
     * Test if can update a tag without payload.
     * 
     * @return void
     */
    public function test_if_can_update_a_tag_without_payload(): void
    {
        $this->putJson(route('tags.update', $this->tag->id))->assertOk();
    }

    /**
     * Test if can update a tag with valid payload.
     * 
     * @return void
     */
    public function test_if_can_update_a_tag_with_valid_payload(): void
    {
        $this->putJson(route('tags.update', $this->tag->id), $this->getValidTagPayload())->assertOk();
    }

    /**
     * Test if can correctly update a tag in database.
     * 
     * @return void
     */
    public function test_if_can_correctly_update_a_tag_in_database(): void
    {
        $this->putJson(route('tags.update', $this->tag->id), $data = $this->getValidTagPayload())->assertOk();

        $this->assertDatabaseHas('tags', [
            'name' => $data['name'],
            'slug' => Str::slug($data['name']),
            'color' => $data['color'],
        ]);
    }

    /**
     * Test if can get correctly tag json structure on update.
     * 
     * @return void
     */
    public function test_if_can_get_correctly_tag_json_structure_on_update(): void
    {
        $this->putJson(route('tags.update', $this->tag->id), $this->getValidTagPayload())->assertOk()->assertJsonStructure([
            'data' => [
                'id',
                'name',
                'slug',
                'color',
                'created_at',
                'updated_at',
            ]
        ]);
    }

    /**
     * Test if can get correctly updated tag json on update.
     * 
     * @return void
     */
    public function test_if_can_get_correctly_updated_tag_json_on_update(): void
    {
        $this->putJson(route('tags.update', $this->tag->id), $data = $this->getValidTagPayload())->assertOk()->assertJson([
            'data' => [
                'id' => $this->tag->id,
                'name' => $data['name'],
                'slug' => Str::slug($data['name']),
                'color' => $data['color'],
                'created_at' => $this->tag->created_at->toIsoString(),
                'updated_at' => $this->tag->updated_at->toIsoString(),
            ]
        ]);
    }
}
