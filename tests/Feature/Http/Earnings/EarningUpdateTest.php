<?php

namespace Tests\Feature\Http\earnings;

use Tests\TestCase;
use Tests\Traits\{HasDummyCategory, HasDummySpace, HasDummyEarning, HasDummyTag, HasDummyUser};

class EarningUpdateTest extends TestCase
{
    use HasDummyTag;
    use HasDummyUser;
    use HasDummySpace;
    use HasDummyCategory;
    use HasDummyEarning;

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
     * The dummy earning.
     * 
     * @var \App\Models\earning
     */
    private $earning;

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
        $this->earning = $this->createDummyEarningTo($this->space);
    }

    /**
     * Get valid earning payload.
     * 
     * @return array
     */
    protected function getValidearningPayload(): array
    {
        return [
            'category_id' => $this->createDummyCategoryTo($this->space)->id,
            'description' => fake()->text(),
            'amount' => fake()->numberBetween(100, 29900),
            'when' => fake()->date(),
            'space_id' => $this->createDummySpaceTo($this->user)->id,
            'tags' => [
                $this->createDummyTagTo($this->user)->id,
                $this->createDummyTagTo($this->user)->id,
            ]
        ];
    }

    /**
     * Test if can update a earning without payload.
     * 
     * @return void
     */
    public function test_if_can_update_a_earning_without_payload(): void
    {
        $this->putJson(route('earnings.update', $this->earning->id))->assertOk();
    }

    /**
     * Test if can throw 404 if earning doesn't exists.
     * 
     * @return void
     */
    public function test_if_can_throw_not_found_if_earning_doesnt_exists(): void
    {
        $this->putJson(route('earnings.update', 9999999))->assertNotFound();
    }

    /**
     * Test if can't update a earning that doesnt belongs to user spaces.
     * 
     * @return void
     */
    public function test_if_cant_update_a_earning_that_doesnt_belongs_to_user_spaces(): void
    {
        $this->putJson(route('earnings.update', $this->createDummyEarning()->id))
            ->assertForbidden()
            ->assertSee('Esta conta n\u00e3o pertence \u00e0 nenhum dos seus espa\u00e7os. Nenhuma opera\u00e7\u00e3o pode ser realizada.');
    }

    /**
     * Test if can't update a earning category with category that doesn't belongs to user space.
     * 
     * @return void
     */
    public function test_if_cant_update_a_earning_category_with_category_that_doesnt_belongs_to_user_space(): void
    {
        $this->putJson(route('earnings.update', $this->earning->id), [
            'category_id' => $this->createDummyCategory()->id,
        ])->assertForbidden()
            ->assertSee('Esta categoria n\u00e3o pertence \u00e0 nenhum dos seus espa\u00e7os. Nenhuma opera\u00e7\u00e3o pode ser realizada.');;
    }

    /**
     * Test if can't update a earning space with space that doesn't belongs to user.
     * 
     * @return void
     */
    public function test_if_cant_update_a_earning_space_with_space_that_doesnt_belongs_to_user(): void
    {
        $this->putJson(route('earnings.update', $this->earning->id), [
            'space_id' => $this->createDummySpace()->id,
        ])->assertForbidden()
            ->assertSee('O espa\u00e7o n\u00e3o pertence ao seu us\u00e1rio. Nenhuma opera\u00e7\u00e3o pode ser realizada.');
    }

    /**
     * Test if can update a earning with correctly payload.
     * 
     * @return void
     */
    public function test_if_can_update_a_earning_with_correctly_payload(): void
    {
        $this->putJson(route('earnings.update', $this->earning->id), $this->getValidearningPayload())->assertOk();
    }

    /**
     * Test if can save updated earning data in database.
     * 
     * @return void
     */
    public function test_if_can_save_payload_correctly_in_database(): void
    {
        $this->putJson(route('earnings.update', $this->earning->id), $data = $this->getValidearningPayload())->assertOk();

        $this->assertDatabaseHas('earnings', [
            'id' => $this->earning->id,
            'description' => $data['description'],
            'amount' => $data['amount'],
            'category_id' => $data['category_id'],
            'space_id' => $data['space_id'],
            'when' => $data['when'],
        ]);
    }

    /**
     * Test if can retrieve correctly json earning structure on update.
     * 
     * @return void
     */
    public function test_if_can_retrieve_correctly_json_earning_structure_on_update(): void
    {
        $this->putJson(route('earnings.update', $this->earning->id), $this->getValidearningPayload())
            ->assertOk()
            ->assertJsonStructure([
                'data' => [
                    'id',
                    'description',
                    'amount',
                    'when',
                    'created_at',
                    'updated_at',
                    'space' => [
                        'id',
                        'name',
                        'slug',
                        'created_at',
                        'updated_at',
                        'currency' => [
                            'id',
                            'name',
                            'iso',
                            'symbol',
                            'created_at',
                            'updated_at',
                        ],
                    ],
                ],
            ]);
    }

    /**
     * Test if can retrieve correctly json updated earning.
     * 
     * @return void
     */
    public function test_if_can_retrieve_correctly_json_updated_earning(): void
    {
        $this->putJson(route('earnings.update', $this->earning->id), $data = $this->getValidearningPayload())->assertOk()->assertJson([
            'data' => [
                'description' => $data['description'],
                'amount' => formatCurrency($data['amount'], $this->space->currency->iso),
                'when' => $data['when'],
                'space' => [
                    'id' => $data['space_id'],
                ],
            ],
        ]);
    }

    /**
     * Test if can create a new activity on earning update.
     * 
     * @return void
     */
    public function test_if_can_create_a_new_activity_on_earning_update(): void
    {
        $this->putJson(route('earnings.update', $this->earning->id), $this->getValidearningPayload())->assertOk();

        $this->assertDatabaseHas('activities', [
            'activitable_type' => $this->earning::class,
            'activitable_id' => $this->earning->id,
            'action' => 'transaction.updated',
        ]);
    }

    /**
     * Test if can associate new tags to earning.
     * 
     * @return void
     */
    public function test_if_can_associate_new_tags_to_earning(): void
    {
        $this->putJson(route('earnings.update', $this->earning->id), $data = $this->getValidEarningPayload())->assertOk();

        $this->assertDatabaseCount('taggable_tags', count($data['tags']));

        foreach ($data['tags'] as $tagId) {
            $this->assertDatabaseHas('taggable_tags', [
                'taggable_type' => $this->earning::class,
                'taggable_id' => $this->earning->id,
                'tag_id' => $tagId
            ]);
        }
    }

    /**
     * Test if can't update earning tags with a tag that doesn't belongs to user.
     * 
     * @return void
     */
    public function test_if_cant_update_earning_tags_with_a_tag_that_doesnt_belongs_to_user(): void
    {
        $this->putJson(route('earnings.update', $this->earning->id), [
            'tags' => [
                $this->createDummyTag()->id,
                $this->createDummyTag()->id,
            ]
        ])->assertForbidden()
            ->assertSee('Uma ou mais tags n\u00e3o pertencem ao seu usu\u00e1rio. Nenhuma opera\u00e7\u00e3o pode ser feita. Tente criar uma nova tag e repetir o processo.');
    }
}
