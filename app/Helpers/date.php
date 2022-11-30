<?php

use Illuminate\Support\Carbon;

if (!function_exists('getYear')) {
    /**
     * Get the current year or given date year.
     * 
     * @param mixed $year
     * @return int
     */
    function getYear(mixed $year = null): int
    {
        if (!is_int($year)) {
            $year = Carbon::parse($year)->year;
        }

        return $year ? $year : Carbon::now()->year;
    }
}

if (!function_exists('getMonth')) {
    /**
     * Get the current month or given date month.
     * 
     * @param mixed $month
     * @return int
     */
    function getMonth(mixed $month = null): int
    {
        if (!is_int($month)) {
            $month = Carbon::parse($month)->month;
        }

        return $month ? $month : Carbon::now()->month;
    }
}
