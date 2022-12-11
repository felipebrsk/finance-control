<?php

namespace Tests\Feature\Http\Auth;

use Tests\TestCase;
use Tests\Traits\HasDummyUser;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class MeUpdateTest extends TestCase
{
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
     * Get the valid me payload.
     *
     * @return array
     */
    protected function getValidMePayload(): array
    {
        return [
            'name' => fake()->name(),
            'username' => fake()->unique()->userName(),
            'avatar' => UploadedFile::fake()->create('avatar.png'),
            'weekly_report' => fake()->boolean(),
            'first_day_of_week' => fake()->randomElement(['monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday']),
        ];
    }

    /**
     * Test if can update without payload.
     *
     * @return void
     */
    public function test_if_can_update_without_payload(): void
    {
        $this->putJson(route('me.update'))->assertOk();
    }

    /**
     * Test if can update with correctly payload.
     *
     * @return void
     */
    public function test_if_can_update_with_correctly_payload(): void
    {
        $this->putJson(route('me.update'), $this->getValidMePayload())->assertOk();
    }

    /**
     * Test if can update the user in database.
     *
     * @return void
     */
    public function test_if_can_update_the_user_in_database(): void
    {
        $this->putJson(route('me.update'), $data = $this->getValidMePayload())->assertOk();

        $this->assertDatabaseHas('users', [
            'id' => $this->user->id,
            'name' => $data['name'],
            'username' => $data['username'],
            'avatar' => 'avatars/' . $data['avatar']->hashName(),
            'weekly_report' => $data['weekly_report'],
            'first_day_of_week' => $data['first_day_of_week'],
        ]);
    }

    /**
     * Test if can get correctly json structure on user update.
     *
     * @return void
     */
    public function test_if_can_get_correctly_json_structure_on_user_update(): void
    {
        $this->putJson(route('me.update'), $this->getValidMePayload())->assertOk()->assertJsonStructure([
            'data' => [
                'id',
                'name',
                'username',
                'email',
                'avatar',
                'weekly_report',
                'first_day_of_week',
            ],
        ]);
    }

    /**
     * Test if can retrieve correctly updated user json data.
     *
     * @return void
     */
    public function test_if_can_retrieve_correctly_updated_user_json_data(): void
    {
        $this->putJson(route('me.update'), $data = $this->getValidMePayload())->assertOk()->assertJson([
            'data' => [
                'id' => $this->user->id,
                'name' => $data['name'],
                'username' => $data['username'],
                'avatar' => 'avatars/' . $data['avatar']->hashName(),
                'weekly_report' => $data['weekly_report'],
                'first_day_of_week' => $data['first_day_of_week'],
            ],
        ]);
    }

    /**
     * Test if can save new avatar in s3.
     *
     * @return void
     */
    public function test_if_can_save_new_avatar_in_s3(): void
    {
        $this->putJson(route('me.update'), $data = $this->getValidMePayload())->assertOk();

        Storage::disk('amazonAws')->assertExists('avatars/' . $data['avatar']->hashName());
    }

    /**
     * Test if can't update username to an existent username.
     *
     * @return void
     */
    public function test_if_cant_update_username_to_an_existent_username(): void
    {
        $this->putJson(route('me.update'), [
            'username' => $this->user->username,
        ])->assertUnprocessable()
            ->assertSee('O campo nome de usu\u00e1rio j\u00e1 est\u00e1 sendo utilizado.');
    }
}
