<?php

namespace Tests\Feature\Http\Spaces;

use Tests\TestCase;
use Illuminate\Support\Str;
use App\Models\{Space, Currency};
use Tests\Traits\{
    HasDummyCurrency,
    HasDummySpace,
    HasDummyTag,
    HasDummyUser
};

class SpaceUpdateTest extends TestCase
{
    use HasDummyTag;
    use HasDummyUser;
    use HasDummySpace;
    use HasDummyCurrency;

    /**
     * The dummy user.
     * 
     * @var \App\Models\User
     */
    private $user;

    /**
     * The dummy space.
     * 
     * @var \App\Models\Space
     */
    private $space;

    /**
     * Setup new test environments.
     * 
     * @return void
     */
    public function setUp(): void
    {
        parent::setUp();

        $this->user = $this->actingAsDummyUser();
        $this->space = $this->createDummySpaceTo($this->user);
    }

    /**
     * Get valid spending payload.
     * 
     * @return array
     */
    protected function getValidSpacePayload(): array
    {
        return [
            'name' => fake()->name(),
            'currency_id' => Currency::whereIso('BRL')->value('id'),
            'tags' => [
                $this->createDummyTagTo($this->user)->id,
                $this->createDummyTagTo($this->user)->id,
            ],
        ];
    }

    /**
     * Test if can throw 404 if space doesn't exists.
     * 
     * @return void
     */
    public function test_if_can_throw_not_found_if_space_doesnt_exists(): void
    {
        $this->putJson(route('spaces.update', 99999999999))->assertNotFound();
    }

    /**
     * Test if can't update another user space.
     * 
     * @return void
     */
    public function test_if_cant_update_another_user_space(): void
    {
        $this->putJson(route('spaces.update', $this->createDummySpace()->id))
            ->assertForbidden()
            ->assertSee('O espa\u00e7o n\u00e3o pertence ao seu us\u00e1rio. Nenhuma opera\u00e7\u00e3o pode ser realizada.');
    }

    /**
     * Test if can't update a space with tags that doesn't belongs to user.
     * 
     * @return void
     */
    public function test_if_cant_update_a_space_with_tags_that_doesnt_belongs_to_user(): void
    {
        $this->putJson(route('spaces.update', $this->space->id), [
            'name' => fake()->name(),
            'currency_id' => $this->createDummyCurrency()->id,
            'tags' => [
                $this->createDummyTag()->id,
                $this->createDummyTag()->id,
            ],
        ])->assertForbidden()
            ->assertSee('Uma ou mais tags n\u00e3o pertencem ao seu usu\u00e1rio e n\u00e3o foi poss\u00edvel associ\u00e1-la. Tente criar uma nova tag e repetir o processo.');
    }

    /**
     * Test if can update a space without payload.
     * 
     * @return void
     */
    public function test_if_can_update_a_space_without_payload(): void
    {
        $this->putJson(route('spaces.update', $this->space->id))->assertOk();
    }

    /**
     * Test if can update a space with valid payload.
     * 
     * @return void
     */
    public function test_if_can_update_a_space_with_valid_payload(): void
    {
        $this->putJson(route('spaces.update', $this->space->id), $this->getValidSpacePayload())->assertOk();
    }

    /**
     * Test if can save space correctly in database.
     * 
     * @return void
     */
    public function test_if_can_save_space_correctly_in_database(): void
    {
        $this->putJson(route('spaces.update', $this->space->id), $data = $this->getValidSpacePayload())->assertOk();

        $this->assertDatabaseHas('spaces', [
            'name' => $data['name'],
            'slug' => Str::slug($data['name']),
            'currency_id' => $data['currency_id'],
            'user_id' => $this->user->id,
        ]);
    }

    /**
     * Test if can associate the tags in database.
     * 
     * @return void
     */
    public function test_if_can_associate_the_tags_in_database(): void
    {
        $this->putJson(route('spaces.update', $this->space->id), $data = $this->getValidSpacePayload())->assertOk();

        $this->assertDatabaseCount('taggable_tags', count($data['tags']));

        foreach ($data['tags'] as $tagId) {
            $this->assertDatabaseHas('taggable_tags', [
                'taggable_type' => Space::class,
                'taggable_id' => $this->space->id,
                'tag_id' => $tagId
            ]);
        }
    }

    /**
     * Test if can return correctly json space structure.
     * 
     * @return void
     */
    public function test_if_can_return_correctly_json_space_structure(): void
    {
        $this->putJson(route('spaces.update', $this->space->id), $this->getValidSpacePayload())->assertOk()->assertJsonStructure([
            'data' => [
                'id',
                'name',
                'slug',
                'created_at',
                'updated_at',
                'monthly_earning_recurrings',
                'monthly_balance',
                'monthly_spending_recurrings',
                'monthly_recurrings_calculated',
                'currency' => [
                    'id',
                    'name',
                    'iso',
                    'symbol',
                    'created_at',
                    'updated_at',
                ],
                'tags' => [
                    '*' => [
                        'id',
                        'name',
                        'slug',
                        'color',
                        'created_at',
                        'updated_at',
                    ],
                ],
            ],
        ]);
    }

    /**
     * Test if can return correctly json spending on creation.
     * 
     * @return void
     */
    public function test_if_can_return_correctly_json_spending_on_creation(): void
    {
        $this->putJson(route('spaces.update', $this->space->id), $data = $this->getValidSpacePayload())->assertOk()->assertJson([
            'data' => [
                'name' => $data['name'],
                'slug' => Str::slug($data['name']),
                'currency' => [
                    'id' => $data['currency_id'],
                ],
                'tags' => [
                    [
                        'id' => $data['tags'][0],
                    ],
                    [
                        'id' => $data['tags'][1],
                    ],
                ],
            ],
        ]);
    }
}
