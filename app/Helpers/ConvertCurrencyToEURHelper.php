<?php

declare(strict_types=1);

namespace App\Helpers;

use Illuminate\Support\Arr;

final class ConvertCurrencyToEURHelper
{
    private const CURRENCY_EUR = 'EUR';

    public static function convert(float $amount, string $currency): float
    {
        if ($currency === self::CURRENCY_EUR) {
            return $amount;
        }

        $rates = config('currency-exchange-rates');

        $rate = Arr::get($rates['rates'], $currency);

        return $amount / $rate;
    }
}
