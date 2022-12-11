<?php

namespace Tests\Unit\Requests\User;

use Tests\TestCase;
use Illuminate\Http\UploadedFile;
use App\Http\Requests\User\UserUpdateRequest;
use Tests\Traits\{HasDummyUser, TestUnitRequests};

class UserUpdateRequestTest extends TestCase
{
    use HasDummyUser;
    use TestUnitRequests;

    /**
     * The request to be tested.
     *
     * @return string
     */
    protected function request(): string
    {
        return UserUpdateRequest::class;
    }

    /**
     * Test name validation rules.
     *
     * @return void
     */
    public function test_name_validation_rules(): void
    {
        $this->assertFalse($this->validateField('name', 123));

        $this->assertTrue($this->validateField('name', ''));
        $this->assertTrue($this->validateField('name', fake()->name()));
    }

    /**
     * Test username validation rules.
     *
     * @return void
     */
    public function test_username_validation_rules(): void
    {
        $this->assertFalse($this->validateField('username', 123));

        $this->assertTrue($this->validateField('username', ''));
        $this->assertTrue($this->validateField('username', fake()->userName()));

        # Existent username
        $this->createDummyUser([
            'username' => $existent = 'existent'
        ]);

        $this->assertFalse($this->validateField('username', $existent));
    }

    /**
     * Test avatar validation rules.
     *
     * @return void
     */
    public function test_avatar_validation_rules(): void
    {
        $this->assertFalse($this->validateField('avatar', '123'));
        $this->assertFalse($this->validateField('avatar', 123));
        $this->assertFalse($this->validateField('avatar', UploadedFile::fake()->create('pic.pdf')));

        $this->assertTrue($this->validateField('avatar', UploadedFile::fake()->create('pic.png')));
    }

    /**
     * Test weekly_report validation rules.
     *
     * @return void
     */
    public function test_weekly_report_validation_rules(): void
    {
        $this->assertFalse($this->validateField('weekly_report', 123));

        $this->assertTrue($this->validateField('weekly_report', ''));
        $this->assertTrue($this->validateField('weekly_report', 1));
        $this->assertTrue($this->validateField('weekly_report', 0));
        $this->assertTrue($this->validateField('weekly_report', true));
        $this->assertTrue($this->validateField('weekly_report', false));
        $this->assertTrue($this->validateField('weekly_report', fake()->boolean()));
    }

    /**
     * Test first_day_of_week validation rules.
     *
     * @return void
     */
    public function test_first_day_of_week_validation_rules(): void
    {
        $this->assertFalse($this->validateField('first_day_of_week', '123'));
        $this->assertFalse($this->validateField('first_day_of_week', 123));

        $this->assertTrue($this->validateField('first_day_of_week', ''));
        $this->assertTrue($this->validateField('first_day_of_week', fake()->randomElement(['monday', 'sunday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday'])));
    }
}
