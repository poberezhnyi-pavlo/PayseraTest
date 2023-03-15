<?php

declare(strict_types=1);

namespace App\Services;

use App\DTO\TransactionDTO;
use App\Fee\FeeFactory;
use Illuminate\Support\Collection;

final class FeeService
{
    public function processFee(Collection $clients): array
    {
        $result = [];
        /** @var TransactionDTO $client */
        foreach ($clients as $client) {
            $feeFactory = FeeFactory::make($client->operationType, $client->clientType);
            $result[] = $feeFactory->calculate($client->amount);
        }

        return $result;
    }
}
