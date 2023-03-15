<?php

declare(strict_types=1);

namespace App\Fee\Rules;

use App\Fee\Interfaces\FeeInterface;
use App\Helpers\RoundFeeHelper;

final class WithdrawBusiness implements FeeInterface
{
    private const COMMISSION_PERCENT = 0.5;
    public function calculate(float $amount): float
    {
        return RoundFeeHelper::round(($amount * self::COMMISSION_PERCENT) / self::PERCENT);
    }
}
