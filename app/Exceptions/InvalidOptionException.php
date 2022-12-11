<?php

namespace App\Exceptions;

use Exception;

class InvalidOptionException extends Exception
{
    /**
     * Display exception for missing from field.
     *
     * @return static
     */
    public static function missingFromField(): static
    {
        return new static('Could not determine which fields should be sluggified');
    }

    /**
     * Display exception for missing slug field.
     *
     * @return static
     */
    public static function missingSlugField(): static
    {
        return new static('Could not determine in which field the slug should be saved');
    }

    /**
     * Display exception for invalid max lenght.
     *
     * @returns static
     */
    public static function invalidMaximumLength(): static
    {
        return new static('Maximum length should be greater than zero');
    }
}
