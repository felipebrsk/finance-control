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

class SpaceStoreTest extends TestCase
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
     * Test if can't create a space without payload.
     *
     * @return void
     */
    public function test_if_cant_create_a_space_without_payload(): void
    {
        $this->postJson(route('spaces.store'))
            ->assertUnprocessable()
            ->assertSee('O campo nome \u00e9 obrigat\u00f3rio. (and 1 more error)');
    }

    /**
     * Test if can't create a space with tags that doesn't belongs to user.
     *
     * @return void
     */
    public function test_if_cant_create_a_space_with_tags_that_doesnt_belongs_to_user(): void
    {
        $this->postJson(route('spaces.store'), [
            'name' => fake()->name(),
            'currency_id' => $this->createDummyCurrency()->id,
            'tags' => [
                $this->createDummyTag()->id,
                $this->createDummyTag()->id,
            ],
        ])->assertForbidden()
            ->assertSee('Uma ou mais tags n\u00e3o pertencem ao seu usu\u00e1rio. Nenhuma opera\u00e7\u00e3o pode ser feita. Tente criar uma nova tag e repetir o processo.');
    }

    /**
     * Test if can create a space with correctly payload.
     *
     * @return void
     */
    public function test_if_can_create_a_space_with_correctly_payload(): void
    {
        $this->postJson(route('spaces.store'), $this->getValidSpacePayload())->assertCreated();
    }

    /**
     * Test if can create correctly space in database.
     *
     * @return void
     */
    public function test_if_can_create_correctly_space_in_database(): void
    {
        $this->postJson(route('spaces.store'), $data = $this->getValidSpacePayload())->assertCreated();

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
        $id = $this->postJson(route('spaces.store'), $data = $this->getValidSpacePayload())->assertCreated()->json('data')['id'];

        $this->assertDatabaseCount('taggable_tags', count($data['tags']));

        foreach ($data['tags'] as $tagId) {
            $this->assertDatabaseHas('taggable_tags', [
                'taggable_type' => Space::class,
                'taggable_id' => $id,
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
        $this->postJson(route('spaces.store'), $this->getValidSpacePayload())->assertCreated()->assertJsonStructure([
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
        $this->postJson(route('spaces.store'), $data = $this->getValidSpacePayload())->assertCreated()->assertJson([
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
