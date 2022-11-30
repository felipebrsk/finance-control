<?php

namespace Tests\Unit\Models;

use Tests\TestCase;
use App\Models\Import;
use Tests\Traits\TestUnitModels;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ImportTest extends TestCase
{
    use TestUnitModels;

    /**
     * The model to be tested.
     *
     * @return string
     */
    protected function model(): string
    {
        return Import::class;
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
            'file',
            'when_column',
            'amount_column',
            'description_column',
            'space_id',
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
        $dates = ['created_at', 'updated_at', 'end_date'];

        $this->verifyDates($dates);
    }

    /**
     * Test the model casts attributes.
     *
     * @return void
     */
    public function test_casts_attribute(): void
    {
        $casts = ['id' => 'int'];

        $this->verifyCasts($casts);
    }
}
