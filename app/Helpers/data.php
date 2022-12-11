<?php

if (!function_exists('issetGetter')) {
    /**
     * Get the given attribute from array if exists.
     *
     * @param array $data
     * @param string $attribute
     * @return mixed
     */
    function issetGetter(string $attribute, array $data): mixed
    {
        return isset($data[$attribute]) ? $data[$attribute] : null;
    }
}
