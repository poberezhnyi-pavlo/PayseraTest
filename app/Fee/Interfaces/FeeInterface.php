<?php

namespace App\Fee\Interfaces;

interface FeeInterface
{
    public const PERCENT = 100;

    public function calculate(float $amount): float;
}
