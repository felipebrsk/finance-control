<?php

namespace Tests\Feature\Http\Auth;

use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class RegisterTest extends TestCase
{
    /**
     * Setup new environment tests.
     * 
     * @return void
     */
    public function setUp(): void
    {
        parent::setUp();
    }

    /**
     * Get the register credentials.
     * 
     * @return array
     */
    private function getRegisterCredentials(): array
    {
        return [
            'name' => fake()->name(),
            'username' => fake()->userName(),
            'email' => fake()->unique()->safeEmail(),
            'password' => 'admin1234',
            'password_confirmation' => 'admin1234',
        ];
    }

    /**
     * Test if can't register without payload.
     * 
     * @return void
     */
    public function test_if_cant_register_without_payload(): void
    {
        $this->postJson(route('register'))->assertUnprocessable()->assertSee('The name field is required. (and 3 more errors)');
    }

    /**
     * Test if can register with validpayload.
     * 
     * @return void
     */
    public function test_if_can_register_with_valid_payload(): void
    {
        $this->postJson(route('register'), $this->getRegisterCredentials())->assertCreated();
    }

    /**
     * Test if can't register with invalid password confirmation.
     * 
     * @return void
     */
    public function test_if_cant_register_with_invalid_password_confirmation(): void
    {
        $data = [
            'name' => fake()->name(),
            'username' => fake()->userName(),
            'email' => fake()->unique()->safeEmail(),
            'password' => 'admin1234',
            'password_confirmation' => 'admin12345',
        ];

        $this->postJson(route('register'), $data)->assertUnprocessable()->assertSee('The password confirmation does not match.');
    }

    /**
     * Test if can't register without password confirmation.
     * 
     * @return void
     */
    public function test_if_cant_register_without_password_confirmation(): void
    {
        $data = [
            'name' => fake()->name(),
            'username' => fake()->userName(),
            'email' => fake()->unique()->safeEmail(),
            'password' => 'admin1234',
        ];

        $this->postJson(route('register'), $data)->assertUnprocessable()->assertSee('The password confirmation does not match.');
    }

    /**
     * Test if can save the correctly user to database.
     * 
     * @return void
     */
    public function test_if_can_save_the_correctly_user_to_database(): void
    {
        $data = $this->getRegisterCredentials();

        $this->postJson(route('register'), $data)->assertCreated();

        $this->assertDatabaseCount('users', 2)->assertDatabaseHas('users', [
            'name' => $data['name'],
            'username' => $data['username'],
            'email' => $data['email'],
        ]);
    }

    /**
     * Test if can hash the user password on creation.
     * 
     * @return void
     */
    public function test_if_can_hash_the_user_password_on_creation(): void
    {
        $data = $this->getRegisterCredentials();

        $this->postJson(route('register'), $data)->assertCreated();

        $this->assertDatabaseMissing('users', [
            'password' => 'admin1234',
        ]);

        $user = User::first();

        $this->assertTrue(Hash::check($data['password'], $user->password));
    }

    /**
     * Check if can return the correctly json structure.
     * 
     * @return void
     */
    public function test_if_can_return_the_correctly_json_structure(): void
    {
        $this->postJson(route('register'), $this->getRegisterCredentials())
            ->assertCreated()
            ->assertJsonStructure([
                'data' => [
                    'token',
                ]
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

        $this->postJson(route('register'), $this->getRegisterCredentials())
            ->assertCreated()
            ->json('data')['token'];

        $this->assertTrue(jwt()->check());

        $this->assertNotNull(jwt()->getToken());
    }

    /**
     * Test if can't submit unsupported profile picture mime type.
     * 
     * @return void
     */
    public function test_if_cant_submit_unsupported_avatar_mime_type(): void
    {
        $this->postJson(route('register'), $this->getRegisterCredentials() + [
            'avatar' => UploadedFile::fake()->create('profile.pdf'),
        ])->assertUnprocessable()
            ->assertInvalid('avatar')
            ->assertSee('The avatar must be a file of type: jpeg, jpg, png, gif.');

        $this->postJson(route('register'), $this->getRegisterCredentials() + [
            'avatar' => UploadedFile::fake()->create('profile.mp4'),
        ])->assertUnprocessable()
            ->assertInvalid('avatar')
            ->assertSee('The avatar must be a file of type: jpeg, jpg, png, gif.');
    }

    /**
     * Test if can submit a profile picture on register.
     * 
     * @return void
     */
    public function test_if_can_submit_a_avatar_on_register(): void
    {
        $this->postJson(route('register'), $this->getRegisterCredentials() + [
            'avatar' => UploadedFile::fake()->create('profile.png'),
        ])->assertCreated();
    }

    /**
     * Test if can save the correctly profile picture s3 path in database.
     * 
     * @return void
     */
    public function test_if_can_save_the_correctly_avatar_s3_path_in_database(): void
    {
        $this->postJson(route('register'), $this->getRegisterCredentials() + [
            'avatar' => $file = UploadedFile::fake()->create('profile.png'),
        ])->assertCreated();

        $this->assertDatabaseHas('users', [
            'avatar' => 'avatars/' . $file->hashName(),
        ]);
    }

    /**
     * Test if can send profile picture to s3.
     * 
     * @return void
     */
    public function test_if_can_send_avatar_to_s3(): void
    {
        $this->postJson(route('register'), $this->getRegisterCredentials() + [
            'avatar' => $file = UploadedFile::fake()->create('profile.png'),
        ])->assertCreated();

        Storage::disk('amazonAws')->assertExists('avatars/' . $file->hashName());
    }

    /**
     * Test if can create a default user space on user creation.
     * 
     * @return void
     */
    public function test_if_can_create_a_default_user_space_on_user_creation(): void
    {
        $this->postJson(route('register'), $data = $this->getRegisterCredentials());

        $user = User::whereEmail($data['email'])->firstOrFail();

        $this->assertDatabaseHas('spaces', [
            'user_id' => $user->id,
            'name' => 'Primeiro espaço de ' . $data['username'],
        ]);
    }
}
