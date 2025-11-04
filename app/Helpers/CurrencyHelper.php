<?php

namespace App\Helpers;

class CurrencyHelper
{
    /**
     * Format a given number into Indonesian Rupiah currency format.
     *
     * @param float|int $amount
     * @return string
     */
    public static function formatRupiah($amount)
    {
        return 'Rp ' . number_format($amount, 0, ',', '.');
    }
}