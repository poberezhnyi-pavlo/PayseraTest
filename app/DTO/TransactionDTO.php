<?php

declare(strict_types=1);

namespace App\DTO;

use Carbon\Carbon;

class TransactionDTO
{
    public Carbon $date;
    public int $clientId;
    public string $clientType;
    public string $operationType;
    public float $amount;
    public string $currency;
    public int $iterationOfWeek;

    public function __construct(
        Carbon $date,
        int $clientId,
        string $clientType,
        string $operationType,
        float $amount,
        string $currency,
        int $iterationOfWeek = 1,
    ) {
        $this->date = $date;
        $this->clientId = $clientId;
        $this->clientType = $clientType;
        $this->operationType = $operationType;
        $this->amount = $amount;
        $this->currency = $currency;

        $this->iterationOfWeek = $iterationOfWeek;
    }
}
