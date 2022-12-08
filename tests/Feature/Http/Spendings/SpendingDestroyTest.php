<?php

namespace Tests\Feature\Http\Spendings;

use Tests\TestCase;
use Tests\Traits\{HasDummySpace, HasDummySpending, HasDummyUser};

class SpendingDestroyTest extends TestCase
{
    use HasDummyUser;
    use HasDummySpace;
    use HasDummySpending;

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
     * The dummy spending.
     * 
     * @var \App\Models\Spending
     */
    private $spending;

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
        $this->spending = $this->createDummySpendingTo($this->space);
    }

    /**
     * Test if can throw 404 if spending doesn't exists.
     * 
     * @return void
     */
    public function test_if_can_throw_not_found_if_spending_doesnt_exists(): void
    {
        $this->deleteJson(route('spendings.destroy', 999999999))->assertNotFound();
    }

    /**
     * Test if can't destroy another user space spending.
     * 
     * @return void
     */
    public function test_if_cant_destroy_another_user_space_spending(): void
    {
        $this->deleteJson(route('spendings.destroy', $this->createDummySpending()->id))
            ->assertForbidden()
            ->assertSee('Esta conta n\u00e3o pertence \u00e0 nenhum dos seus espa\u00e7os. Nenhuma opera\u00e7\u00e3o pode ser realizada.');
    }

    /**
     * Test if can destroy own space spending.
     * 
     * @return void
     */
    public function test_if_can_destroy_own_space_spending(): void
    {
        $this->deleteJson(route('spendings.destroy', $this->spending->id))->assertOk();
    }

    /**
     * Test if can soft delete spending.
     * 
     * @return void
     */
    public function test_if_can_soft_delete_spending(): void
    {
        $this->assertTrue($this->spending->exists());

        $this->deleteJson(route('spendings.destroy', $this->spending->id))->assertOk();

        $this->assertFalse($this->spending->exists());
    }
}
