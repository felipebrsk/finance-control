<?php

namespace App\Traits;

use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Model;
use App\Exceptions\InvalidOptionException;
use App\Helpers\SlugOptions;

trait HasSlug
{
    /**
     * Get the slug options
     * 
     * @var \App\Helpers\SlugOptions
     */
    protected SlugOptions $slugOptions;

    /**
     * Get the slug options.
     * 
     * @return \App\Helpers\SlugOptions
     */
    abstract public function getSlugOptions(): SlugOptions;

    /**
     * Boot the model events.
     * 
     * @method static creating(\Closure $callback)
     * @return void
     */
    protected static function bootHasSlug(): void
    {
        static::creating(function (Model $model) {
            $model->generateSlugOnCreate();
        });

        static::updating(function (Model $model) {
            $model->generateSlugOnUpdate();
        });
    }


    /**
     * Generate the slug on model creation.
     * 
     * @return void
     */
    protected function generateSlugOnCreate(): void
    {
        $this->slugOptions = $this->getSlugOptions();

        if ($this->slugOptions->skipGenerate) {
            return;
        }

        $this->createSlug();
    }

    /**
     * Generate the slug on model updating.
     * 
     * @return void
     */
    protected function generateSlugOnUpdate(): void
    {
        $this->slugOptions = $this->getSlugOptions();

        if ($this->slugOptions->skipGenerate) {
            return;
        }

        $this->createSlug();
    }

    /**
     * Generate the model slug.
     * 
     * @return void
     */
    public function generateSlug(): void
    {
        $this->slugOptions = $this->getSlugOptions();

        $this->createSlug();
    }

    /**
     * Add the model slug.
     * 
     * @return void
     */
    protected function createSlug(): void
    {
        $this->ensureValidSlugService();

        $slug = $this->generateNonUniqueSlug();

        $slug = $this->makeSlugUnique($slug);

        $slugField = $this->slugOptions->slugField;

        $this->$slugField = $slug;
    }

    /**
     * Generate a non unique slug.
     * 
     * @return string
     */
    protected function generateNonUniqueSlug(): string
    {
        $slugField = $this->slugOptions->slugField;

        if ($this->hasCustomSlugBeenUsed() && !empty($this->$slugField)) {
            return $this->$slugField;
        }

        return Str::slug($this->getSlugSourceString());
    }

    /**
     * Get the bool of custom slug been used.
     * 
     * @return bool
     */
    protected function hasCustomSlugBeenUsed(): bool
    {
        $slugField = $this->slugOptions->slugField;

        return $this->getOriginal($slugField) != $this->$slugField;
    }

    /**
     * Get the slug source string.
     * 
     * @return string
     */
    protected function getSlugSourceString(): string
    {
        if (is_callable($this->slugOptions->generateSlugFrom)) {
            $slugSourceString = $this->getSlugSourceStringFromCallable();

            return $this->generateSubstring($slugSourceString);
        }

        $slugSourceString = collect($this->slugOptions->generateSlugFrom)
            ->map(fn (string $fieldName): string => data_get($this, $fieldName, ''))
            ->implode('-');

        return $this->generateSubstring($slugSourceString);
    }

    /**
     * Get the slug source string from a callable function.
     * 
     * @return string
     */
    protected function getSlugSourceStringFromCallable(): string
    {
        return call_user_func($this->slugOptions->generateSlugFrom, $this);
    }

    /**
     * Make the slug unique.
     * 
     * @param string $slug
     * @return string
     */
    protected function makeSlugUnique(string $slug): string
    {
        $originalSlug = $slug;

        $i = 1;

        while ($this->otherRecordExistsWithSlug($slug) || $slug === '') {
            $slug = $originalSlug . '-' . $i++;
        }

        return $slug;
    }

    /**
     * Check if other record exists with given slug.
     * 
     * @param string $slug
     * @return bool
     */
    protected function otherRecordExistsWithSlug(string $slug): bool
    {
        $query = static::where($this->slugOptions->slugField, $slug)
            ->withoutGlobalScopes();

        if ($this->exists) {
            $query->where($this->getKeyName(), '!=', $this->getKey());
        }

        if ($this->usesSoftDeletes()) {
            $query->withTrashed();
        }

        return $query->exists();
    }

    /**
     * Use soft deletes.
     * 
     * @return bool
     */
    protected function usesSoftDeletes(): bool
    {
        return in_array('Illuminate\Database\Eloquent\SoftDeletes', class_uses($this), true);
    }

    /**
     * Check if slug is valid.
     * 
     * @return void
     */
    protected function ensureValidSlugService(): void
    {
        if (is_array($this->slugOptions->generateSlugFrom) && !count($this->slugOptions->generateSlugFrom)) {
            throw InvalidOptionException::missingFromField();
        }

        if (!strlen($this->slugOptions->slugField)) {
            throw InvalidOptionException::missingSlugField();
        }

        if ($this->slugOptions->maximumLength <= 0) {
            throw InvalidOptionException::invalidMaximumLength();
        }
    }

    /**
     * Handle multi-bytes strings if the module mb_substr is present.
     * 
     * @param mixed $slugSourceString
     * @return string
     */
    protected function generateSubstring(mixed $slugSourceString): string
    {
        if (function_exists('mb_substr')) {
            return mb_substr($slugSourceString, 0, $this->slugOptions->maximumLength);
        }

        return substr($slugSourceString, 0, $this->slugOptions->maximumLength);
    }
}
