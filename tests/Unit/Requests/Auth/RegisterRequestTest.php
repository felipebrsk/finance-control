<?php

namespace Tests\Unit\Requests\Auth;

use Tests\TestCase;
use Tests\Traits\HasDummyUser;
use Tests\Traits\TestUnitRequests;
use App\Http\Requests\Auth\RegisterRequest;
use Illuminate\Http\UploadedFile;

class RegisterRequestTest extends TestCase
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
        return RegisterRequest::class;
    }

    /**
     * Test name validation rules.
     * 
     * @return void
     */
    public function test_name_validation_rules(): void
    {
        $this->assertFalse($this->validateField('name', ''));
        $this->assertFalse($this->validateField('name', 123));
        $this->assertTrue($this->validateField('name', fake()->name()));
    }

    /**
     * Test username validation rules.
     * 
     * @return void
     */
    public function test_username_validation_rules(): void
    {
        $this->assertFalse($this->validateField('username', ''));
        $this->assertFalse($this->validateField('username', 123));
        $this->assertTrue($this->validateField('username', fake()->userName()));

        # Unique validations.
        $this->createDummyUser([
            'username' => $username = fake()->userName(),
        ]);

        $this->assertFalse($this->validateField('username', $username));
    }

    /**
     * Test email validation rules.
     * 
     * @return void
     */
    public function test_email_validation_rules(): void
    {
        $this->assertFalse($this->validateField('email', ''));
        $this->assertFalse($this->validateField('email', 123));
        $this->assertFalse($this->validateField('email', 'john.doe'));
        $this->assertTrue($this->validateField('email', fake()->safeEmail()));

        # Unique validations.
        $this->createDummyUser([
            'email' => $email = fake()->email(),
        ]);

        $this->assertFalse($this->validateField('email', $email));
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
     * Test weekly report validation rules.
     * 
     * @return void
     */
    public function test_weekly_report_validation_rules(): void
    {
        $this->assertFalse($this->validateField('weekly_report', '123'));
        $this->assertFalse($this->validateField('weekly_report', 123));
        $this->assertTrue($this->validateField('weekly_report', fake()->boolean()));
    }

    /**
     * Test first day of week validation rules.
     * 
     * @return void
     */
    public function test_first_day_of_week_validation_rules(): void
    {
        $this->assertFalse($this->validateField('first_day_of_week', '123'));
        $this->assertFalse($this->validateField('first_day_of_week', 123));
        $this->assertTrue($this->validateField('first_day_of_week', fake()->randomElement(['monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday'])));
    }
}
