<?php

namespace Tests\Unit\Models;

use Tests\TestCase;
use App\Models\Earning;
use Tests\Traits\TestUnitModels;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class EarningTest extends TestCase
{
    use TestUnitModels;

    /**
     * The model to be tested.
     *
     * @return string
     */
    protected function model(): string
    {
        return Earning::class;
    }

    /**
     * Test the model fillable attributes.
     *
     * @return void
     */
    public function test_fillable(): void
    {
        $fillable = [
            'when',
            'amount',
            'description',
            'space_id',
            'recurring_id',
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
            SoftDeletes::class,
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
        $dates = ['created_at', 'updated_at', 'deleted_at'];

        $this->verifyDates($dates);
    }

    /**
     * Test the model casts attributes.
     *
     * @return void
     */
    public function test_casts_attribute(): void
    {
        $casts = ['id' => 'int', 'deleted_at' => 'datetime'];

        $this->verifyCasts($casts);
    }
}
