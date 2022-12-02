<?php

namespace App\Helpers;

class SlugOptions
{
    /**
     * Generate the slug from column.
     * 
     * @var array|\Closure
     */
    public $generateSlugFrom = 'name';

    /**
     * The slug field.
     * 
     * @var string
     */
    public string $slugField = 'slug';

    /**
     * The slug max lenght.
     * 
     * @var int
     */
    public int $maximumLength = 50;

    /**
     * Skip slug generation.
     * 
     * @var bool
     */
    public bool $skipGenerate = false;

    /**
     * The create method.
     * 
     * @return static
     */
    public static function create(): static
    {
        return new static();
    }

    /**
     * Generate the slug from given column.
     * 
     * @param string|array|callable $fieldName
     * @return self
     */
    public function generateSlugsFrom(string|array|callable $fieldName): self
    {
        if (is_string($fieldName)) {
            $fieldName = [$fieldName];
        }

        $this->generateSlugFrom = $fieldName;

        return $this;
    }

    /**
     * Save slug to column.
     * 
     * @param string $fieldName
     * @return self
     */
    public function saveSlugsTo(string $fieldName): self
    {
        $this->slugField = $fieldName;

        return $this;
    }

    /**
     * Prevent slugs longer than given value.
     * 
     * @param int $maximunLenght
     * @return self
     */
    public function slugsShouldBeNoLongerThan(int $maximumLength): self
    {
        $this->maximumLength = $maximumLength;

        return $this;
    }

    /**
     * Skip the slug generation when.
     * 
     * @param callable $callable
     * @return self
     */
    public function skipGenerateWhen(callable $callable): self
    {
        $this->skipGenerate = $callable() === true;

        return $this;
    }
}
