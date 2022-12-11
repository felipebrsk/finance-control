<?php


if (!function_exists('formatCurrency')) {
    /**
     * Format the money to currency.
     *
     * @param int $amount
     * @param string $currency
     * @return string
     */
    function formatCurrency(int $amount, string $currency): string
    {
        $formatter = new NumberFormatter('pt_BR', NumberFormatter::CURRENCY);

        return $formatter->formatCurrency($amount / 100, $currency);
    }
}
