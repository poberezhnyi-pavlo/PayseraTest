<?php

declare(strict_types=1);

namespace App\Fee\Rules;

use App\Fee\Interfaces\FeeInterface;
use App\Helpers\RoundFeeHelper;

final class WithdrawPrivateFee implements FeeInterface
{
    private const COMMISSION_PERCENT = 0.3;
    public function calculate(float $amount): float
    {
        return RoundFeeHelper::round(($amount * self::COMMISSION_PERCENT) / self::PERCENT);
    }
}
