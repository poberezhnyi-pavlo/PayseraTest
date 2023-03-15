<?php

declare(strict_types=1);

namespace App\Helpers;

class RoundFeeHelper
{
    public static function round(float $number): float
    {
        return ceil($number * 100) / 100;
    }
}
