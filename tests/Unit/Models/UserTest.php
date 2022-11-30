<?php

namespace Tests\Unit\Models;

use Tests\TestCase;
use App\Models\User;
use App\Traits\HasPassword;
use Tests\Traits\TestUnitModels;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class UserTest extends TestCase
{
    use TestUnitModels;

    /**
     * The model to be tested.
     *
     * @return string
     */
    protected function model(): string
    {
        return User::class;
    }

    /**
     * Test the model fillable attributes.
     *
     * @return void
     */
    public function test_fillable(): void
    {
        $fillable = [
            'name',
            'email',
            'avatar',
            'username',
            'password',
            'weekly_report',
            'first_day_of_week',
        ];

        $this->verifyIfExistFillable($fillable);
    }

    /**
     * Test if the model uses the correctly traits.
     *
     * @return void
     */
    public function test_if_use_traits(): void
    {
        $traits = [
            HasFactory::class,
            Notifiable::class,
            HasPassword::class,
            HasApiTokens::class,
        ];

        $this->verifyIfUseTraits($traits);
    }

    /**
     * Test the model dates attributes.
     *
     * @return void
     */
    public function test_dates_attribute(): void
    {
        $dates = ['created_at', 'updated_at'];

        $this->verifyDates($dates);
    }

    /**
     * Test the model casts attributes.
     *
     * @return void
     */
    public function test_casts_attribute(): void
    {
        $casts = ['id' => 'int', 'email_verified_at' => 'datetime'];

        $this->verifyCasts($casts);
    }
}
