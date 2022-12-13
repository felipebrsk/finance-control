<?php

namespace Tests\Feature\Http\Auth;

use App\Events\User\UserPasswordUpdated;
use Tests\TestCase;
use Tests\Traits\HasDummyUser;
use Illuminate\Support\Carbon;
use App\Jobs\PasswordChangedJob;
use App\Mail\PasswordChangedMail;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\{Bus, DB, Event, Hash, Mail, Queue};

class PasswordResetTest extends TestCase
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
     * The dummy token.
     *
     * @var string
     */
    private $token;

    /**
     * Setup new test environments.
     *
     * @return void
     */
    public function setUp(): void
    {
        parent::setUp();

        $this->user = $this->actingAsDummyUser();
        $this->token = 'hashedtokendatabase';

        DB::table('password_resets')->insert([
            'email' => $this->user->email,
            'token' => Hash::make($this->token),
            'created_at' => Carbon::now(),
        ]);
    }

    /**
     * Test if can't reset password with inexistent token.
     *
     * @return void
     */
    public function test_if_cant_reset_password_with_inexistent_token(): void
    {
        $this->postJson(route('password.reset'), [
            'email' => $this->user->email,
            'token' => 'inexistent',
            'password' => 'secret1234',
            'password_confirmation' => 'secret1234',
        ])->assertUnprocessable()
            ->assertSee('Este token de redefini\u00e7\u00e3o de senha \u00e9 inv\u00e1lido.');
    }

    /**
     * Test if can't reset password with inexistent email.
     *
     * @return void
     */
    public function test_if_cant_reset_password_with_inexistent_email(): void
    {
        $this->postJson(route('password.reset'), [
            'email' => 'inexistent@gmail.com',
            'token' => 'inexistent',
            'password' => 'secret1234',
            'password_confirmation' => 'secret1234',
        ])->assertUnprocessable()
            ->assertSee("N\u00e3o encontramos um usu\u00e1rio com esse endere\u00e7o de e-mail.", false);
    }

    /**
     * Test if can reset password with existent reset token.
     *
     * @return void
     */
    public function test_if_can_reset_password_with_existent_reset_token(): void
    {
        $this->postJson(route('password.reset'), [
            'email' => $this->user->email,
            'token' => $this->token,
            'password' => 'secret1234',
            'password_confirmation' => 'secret1234',
        ])->assertOk()
            ->assertSee('Sua senha foi redefinida!');
    }

    /**
     * Test if can reset password in database.
     *
     * @return void
     */
    public function test_if_can_reset_password_in_database(): void
    {
        $this->assertTrue(Hash::check('admin1234', DB::table('users')->whereEmail($this->user->email)->value('password')));

        $this->postJson(route('password.reset'), [
            'email' => $this->user->email,
            'token' => $this->token,
            'password' => 'secret1234',
            'password_confirmation' => 'secret1234',
        ])->assertOk()
            ->assertSee('Sua senha foi redefinida!');

        $this->assertTrue(Hash::check('secret1234', DB::table('users')->whereEmail($this->user->email)->value('password')));
    }

    /**
     * Test if can login with new password.
     *
     * @return void
     */
    public function test_if_can_login_with_new_password(): void
    {
        $this->postJson(route('login'), [
            'credential' => $this->user->email,
            'password' => 'admin1234',
        ])->assertOk();

        $this->postJson(route('password.reset'), [
            'email' => $this->user->email,
            'token' => $this->token,
            'password' => 'secret1234',
            'password_confirmation' => 'secret1234',
        ])->assertOk()
            ->assertSee('Sua senha foi redefinida!');

        $this->postJson(route('login'), [
            'credential' => $this->user->email,
            'password' => 'secret1234',
        ])->assertOk();
    }

    /**
     * Test if can send the email reset notification.
     *
     * @return void
     */
    public function test_if_can_send_the_email_reset_notification(): void
    {
        Mail::fake();

        $this->postJson(route('password.reset'), [
            'email' => $this->user->email,
            'token' => $this->token,
            'password' => 'secret1234',
            'password_confirmation' => 'secret1234',
        ])->assertOk()
            ->assertSee('Sua senha foi redefinida!');

        Mail::assertQueued(PasswordChangedMail::class, 1);
    }

    /**
     * Test if the job was pushed to queue.
     *
     * @return void
     */
    public function test_if_the_job_was_pushed_to_queue(): void
    {
        Queue::fake();

        $this->postJson(route('password.reset'), [
            'email' => $this->user->email,
            'token' => $this->token,
            'password' => 'secret1234',
            'password_confirmation' => 'secret1234',
        ])->assertOk()
            ->assertSee('Sua senha foi redefinida!');

        Queue::assertPushed(PasswordChangedJob::class, function (PasswordChangedJob $job) {
            return $this->user->id === $job->user->id;
        });
    }

    /**
     * Test if the job was dispatched.
     *
     * @return void
     */
    public function test_if_the_job_was_dispatched(): void
    {
        Bus::fake();

        $this->postJson(route('password.reset'), [
            'email' => $this->user->email,
            'token' => $this->token,
            'password' => 'secret1234',
            'password_confirmation' => 'secret1234',
        ])->assertOk()
            ->assertSee('Sua senha foi redefinida!');

        Bus::assertDispatched(PasswordChangedJob::class, function (PasswordChangedJob $job) {
            return $this->user->id === $job->user->id;
        });
    }

    /**
     * Test if can dispatch the user password updated event on password change.
     *
     * @return void
     */
    public function test_if_can_dispatch_the_user_password_updated_event_on_password_change(): void
    {
        Event::fake([UserPasswordUpdated::class]);

        $this->postJson(route('password.reset'), [
            'email' => $this->user->email,
            'token' => $this->token,
            'password' => 'secret1234',
            'password_confirmation' => 'secret1234',
        ])->assertOk()
            ->assertSee('Sua senha foi redefinida!');

        Event::assertDispatched(UserPasswordUpdated::class);
    }

    /**
     * Test if can generate a new activity on password change.
     *
     * @return void
     */
    public function test_if_can_generate_a_new_activity_on_password_change(): void
    {
        $this->postJson(route('password.reset'), [
            'email' => $this->user->email,
            'token' => $this->token,
            'password' => 'secret1234',
            'password_confirmation' => 'secret1234',
        ])->assertOk()
            ->assertSee('Sua senha foi redefinida!');

        $this->assertDatabaseHas('activities', [
            'activitable_id' => $this->user->id,
            'activitable_type' => $this->user::class,
            'action' => 'password.changed',
        ]);
    }
}
