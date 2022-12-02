<?php

namespace Tests\Feature\Http\Auth;

use Tests\TestCase;
use Tests\Traits\HasDummyUser;
use Illuminate\Support\Facades\Notification;
use App\Notifications\Auth\QueuedResetPassword;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ForgotPasswordTest extends TestCase
{
    use RefreshDatabase;
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
     * Test if can't send a reset link to an inexistent email.
     * 
     * @return void
     */
    public function test_if_cant_send_a_reset_link_to_an_inexistent_email(): void
    {
        $this->postJson(route('password.forgot'), [
            'email' => 'inexistent@gmail.com',
        ])->assertUnprocessable()
            ->assertInvalid('email')
            ->assertSee("We can't find a user with that email address.", false);
    }

    /**
     * Test if can send a reset link to an existent email.
     * 
     * @return void
     */
    public function test_if_can_send_a_reset_link_to_an_existent_email(): void
    {
        $this->postJson(route('password.forgot'), [
            'email' => $this->user->email,
        ])->assertOk();
    }

    /**
     * Test if can send the reset email link notification.
     * 
     * @return void
     */
    public function test_if_can_send_the_reset_email_link_notification(): void
    {
        Notification::fake();

        $this->postJson(route('password.forgot'), [
            'email' => $this->user->email,
        ])->assertOk();

        Notification::assertSentTo($this->user, QueuedResetPassword::class);
    }

    /**
     * Test if can save the request on database.
     * 
     * @return void
     */
    public function test_if_can_save_the_request_on_database(): void
    {
        $this->postJson(route('password.forgot'), [
            'email' => $this->user->email,
        ])->assertOk();

        $this->assertDatabaseCount('password_resets', 1)->assertDatabaseHas('password_resets', [
            'email' => $this->user->email,
        ]);
    }

    /**
     * Test if can't request password forgot many times at once.
     * 
     * @return void
     */
    public function test_if_cant_request_password_forgot_many_times_at_once(): void
    {
        $this->postJson(route('password.forgot'), [
            'email' => $this->user->email,
        ])->assertOk();

        $this->postJson(route('password.forgot'), [
            'email' => $this->user->email,
        ])->assertUnprocessable()
            ->assertSee('Please wait before retrying.');
    }
}
