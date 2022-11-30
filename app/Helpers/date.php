<?php

use Illuminate\Support\Carbon;

if (!function_exists('getCurrentYear')) {
    /**
     * Get the current year.
     * 
     * @param int $year
     * @return int
     */
    function getCurrentYear(int $year = null)
    {
        return $year ? $year : Carbon::now()->year;
    }
}

if (!function_exists('getCurrentMonth')) {
    /**
     * Get the current month.
     * 
     * @param int $month
     * @return int
     */
    function getCurrentMonth(int $month = null)
    {
        return $month ? $month : Carbon::now()->month;
    }
}
