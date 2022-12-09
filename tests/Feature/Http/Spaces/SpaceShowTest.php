<?php

namespace Tests\Feature\Http\Spaces;

use Tests\TestCase;
use Tests\Traits\{HasDummyCategory, HasDummySpace, HasDummyTag, HasDummyUser};

class SpaceShowTest extends TestCase
{
    use HasDummyTag;
    use HasDummyUser;
    use HasDummySpace;
    use HasDummyCategory;

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
     * The dummy tag.
     * 
     * @var \App\Models\Tag
     */
    private $tag;

    /**
     * The dummy category.
     * 
     * @var \App\Models\Category
     */
    private $category;

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
        $this->tag = $this->createDummyTag();
        $this->space->tags()->save($this->tag)->save();
        $this->category = $this->createDummyCategoryTo($this->space);
    }

    /**
     * Test if can throw 404 if space doesn't exists.
     * 
     * @return void
     */
    public function test_if_can_throw_not_found_if_space_doesnt_exists(): void
    {
        $this->getJson(route('spaces.show', 999999999))->assertNotFound();
    }

    /**
     * Test if can't see another user space.
     * 
     * @return void
     */
    public function test_if_cant_see_another_user_space(): void
    {
        $this->getJson(route('spaces.show', $this->createDummySpace()->id))
            ->assertForbidden()
            ->assertSee('O espa\u00e7o n\u00e3o pertence ao seu us\u00e1rio. Nenhuma opera\u00e7\u00e3o pode ser realizada.');
    }

    /**
     * Test if can see own space details.
     * 
     * @return void
     */
    public function test_if_can_see_own_space_details(): void
    {
        $this->getJson(route('spaces.show', $this->space->id))->assertOk();
    }

    /**
     * Test if can see correctly json space structure.
     * 
     * @return void
     */
    public function test_if_can_see_correctly_json_space_structure(): void
    {
        $this->getJson(route('spaces.show', $this->space->id))->assertOk()->assertJsonStructure([
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
                'categories' => [
                    '*' => [
                        'id',
                        'name',
                        'slug',
                        'created_at',
                        'updated_at',
                    ],
                ],
            ],
        ]);
    }

    /**
     * Test if can get correctly json space.
     * 
     * @return void
     */
    public function test_if_can_get_correctly_json_space(): void
    {
        $this->getJson(route('spaces.show', $this->space->id))->assertOk()->assertJson([
            'data' => [
                'id' => $this->space->id,
                'name' => $this->space->name,
                'slug' => $this->space->slug,
                'created_at' => $this->space->created_at->toIsoString(),
                'updated_at' => $this->space->updated_at->toIsoString(),
                'monthly_earning_recurrings' => formatCurrency($this->space->getMonthlyEarningRecurrings(), $this->space->currency->iso),
                'monthly_balance' => formatCurrency($this->space->getMonthlyBalance(), $this->space->currency->iso),
                'monthly_spending_recurrings' => formatCurrency($this->space->getMonthlySpendingRecurrings(), $this->space->currency->iso),
                'monthly_recurrings_calculated' => formatCurrency($this->space->calculateMonthlyRecurrings(), $this->space->currency->iso),
                'currency' => [
                    'id' => $this->space->currency->id,
                    'name' => $this->space->currency->name,
                    'iso' => $this->space->currency->iso,
                    'symbol' => $this->space->currency->symbol,
                    'created_at' => $this->space->currency->created_at->toIsoString(),
                    'updated_at' => $this->space->currency->updated_at->toIsoString(),
                ],
                'tags' => [
                    [
                        'id' => $this->tag->id,
                        'name' => $this->tag->name,
                        'slug' => $this->tag->slug,
                        'color' => $this->tag->color,
                        'created_at' => $this->tag->created_at->toIsoString(),
                        'updated_at' => $this->tag->updated_at->toIsoString(),
                    ],
                ],
                'categories' => [
                    [
                        'id' => $this->category->id,
                        'name' => $this->category->name,
                        'slug' => $this->category->slug,
                        'created_at' => $this->category->created_at->toIsoString(),
                        'updated_at' => $this->category->updated_at->toIsoString(),
                    ],
                ],
            ],
        ]);
    }
}
