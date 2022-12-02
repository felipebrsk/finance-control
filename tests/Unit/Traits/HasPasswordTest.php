<?php

namespace Tests\Unit\Traits;

use Tests\TestCase;
use Tests\Traits\HasDummyUser;
use Illuminate\Support\Facades\Hash;
use PHPOpenSourceSaver\JWTAuth\Facades\JWTAuth;

class HasPasswordTest extends TestCase
{
    use HasDummyUser;

    /**
     * Setup new has password trait environments.
     *
     * @return void
     */
    public function setUp(): void
    {
        parent::setUp();
    }

    /**
     * Test if can hash the password on user creation.
     *
     * @return void
     */
    public function test_if_can_hash_the_password_on_user_creation(): void
    {
        $user = $this->createDummyUser([
            'password' => 'admin1234'
        ]);

        $this->assertDatabaseMissing('users', [
            'password' => 'admin1234'
        ]);

        $this->assertTrue(Hash::check('admin1234', $user->password));
    }

    /**
     * Test if can hash on user register.
     *
     * @return void
     */
    public function test_if_can_hash_password_field_on_user_register(): void
    {
        $this->postJson(route('register'), [
            'name' => fake()->name(),
            'username' => fake()->userName(),
            'email' => fake()->safeEmail(),
            'password' => 'admin1234',
            'password_confirmation' => 'admin1234',
        ])->assertCreated()->assertJsonStructure([
            'data' => [
                'token'
            ]
        ])->assertSee('token');

        $this->assertDatabaseMissing('users', [
            'password' => 'admin1234',
        ]);

        $user = JWTAuth::user();

        $this->assertTrue(Hash::check('admin1234', $user->password));

        $this->assertAuthenticatedAs($user);
    }
}
