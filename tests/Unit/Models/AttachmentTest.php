<?php

namespace Tests\Unit\Models;

use Tests\TestCase;
use App\Models\Attachment;
use Tests\Traits\TestUnitModels;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class AttachmentTest extends TestCase
{
    use TestUnitModels;

    /**
     * The model to be tested.
     *
     * @return string
     */
    protected function model(): string
    {
        return Attachment::class;
    }

    /**
     * Test the model fillable attributes.
     *
     * @return void
     */
    public function test_fillable(): void
    {
        $fillable = [
            'attachment',
            'attachmentable_id',
            'attachmentable_type',
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
