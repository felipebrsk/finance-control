<?php

namespace Tests\Feature\Http\Auth;

use Tests\TestCase;
use Tests\Traits\HasDummyUser;

class LoginTest extends TestCase
{
    use HasDummyUser;

    /**
     * The dummy user.
     *
     * @var \App\Models\User
     */
    private $user;

    /**
     * Setup new environment tests.
     *
     * @return void
     */
    public function setUp(): void
    {
        parent::setUp();

        $this->user = $this->createDummyUser([
            'username' => 'user',
            'email' => 'user@gmail.com',
            'password' => 'admin1234',
        ]);
    }

    /**
     * Test if can login with valid user and username.
     *
     * @return void
     */
    public function test_if_can_login_with_valid_user_and_username(): void
    {
        $this->postJson(route('login'), [
            'credential' => $this->user->username,
            'password' => 'admin1234',
        ])->assertOk();
    }

    /**
     * Test if can login with valid user and email.
     *
     * @return void
     */
    public function test_if_can_login_with_valid_user_and_email(): void
    {
        $this->postJson(route('login'), [
            'credential' => $this->user->email,
            'password' => 'admin1234',
        ])->assertOk();
    }

    /**
     * Test if can't login with invalid credentials.
     *
     * @return void
     */
    public function test_if_cant_login_with_invalid_credentials(): void
    {
        $this->postJson(route('login'), [
            'credential' => $this->user->email,
            'password' => 'admin12345',
        ])->assertUnauthorized()->assertSee('We could not find the user or the password is wrong. Please, double check the informations and try again!');
    }

    /**
     * Test if can respond with bearer token if credentials are valid.
     *
     * @return void
     */
    public function test_if_can_respond_with_bearer_token_if_credentials_are_valid(): void
    {
        $this->postJson(route('login'), [
            'credential' => $this->user->email,
            'password' => 'admin1234',
        ])->assertOk()->assertJsonStructure([
            'data' => [
                'token',
            ],
        ]);
    }

    /**
     * Check if JWT can validate generated token.
     *
     * @return void
     */
    public function test_if_JWT_can_validate_generated_token(): void
    {
        $this->assertFalse(jwt()->check());

        $this->assertNull(jwt()->getToken());

        $this->postJson(route('login'), [
            'credential' => $this->user->email,
            'password' => 'admin1234',
        ])->assertOk()->json('data')['token'];

        $this->assertTrue(jwt()->check());

        $this->assertNotNull(jwt()->getToken());
    }
}
