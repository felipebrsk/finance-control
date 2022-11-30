<?php

namespace App\Traits;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Hash;

/**
 *  @method static creating(\Closure $param)
 */
trait HasPassword
{
    /**
     * Set the password attribute hashed on creating event.
     *
     * @return void
     */
    public static function bootHasPassword(): void
    {
        static::creating(function (Model $model) {
            $model->password = Hash::make($model->password);
        });
    }
}
