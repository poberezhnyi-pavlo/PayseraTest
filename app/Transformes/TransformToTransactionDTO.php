<?php

declare(strict_types=1);

namespace App\Transformes;

use App\DTO\TransactionDTO;
use App\Helpers\ConvertCurrencyToEURHelper;
use Carbon\Carbon;
use Illuminate\Support\Arr;

final class TransformToTransactionDTO
{
    public static array $withdrawPrivateClient = [];
    private const MAX_FREE_ITERATION = 3;
    private const DAYS_IN_WEEK = 7;
    private const AMOUNT_FREE = 1000.0;

    public function transform(array $data): TransactionDTO
    {
        $dto = new TransactionDTO(
            Carbon::parse($data[0]),
            (int) $data[1],
            $data[2],
            $data[3],
            ConvertCurrencyToEURHelper::convert((float)$data[4], $data[5]),
            $data[5]
        );

        if ($dto->clientType === 'private' && $dto->operationType === 'withdraw') {
            $filterByClient = array_filter(self::$withdrawPrivateClient, static function($item) use ($dto) {
                return $item['client_id'] === $dto->clientId && $item['client_type'] === 'private' && $item['operation_type'] === 'withdraw';
            });

            if (! empty($filterByClient)) {
                $last = Arr::last($filterByClient);

                if ($this->isCurrentWeek($last['date'], $dto->date)) {
                    if ($last['iteration_of_week'] <= self::MAX_FREE_ITERATION) {
                        $dto->iterationOfWeek = $last['iteration_of_week'] + 1;

                        $sum = $last['sum'] + $dto->amount;

                        $dto->amount = $this->calculateAmount($sum, $dto->amount);

                        $this->setWithdrawPrivateClient($dto, $sum, $last['iteration_of_week'] + 1);
                    } else {
                        $this->setWithdrawPrivateClient($dto, $dto->amount, 1);
                    }
                } else {
                    $sum = $dto->amount;

                    $dto->amount = $this->calculateAmount($sum, $dto->amount);

                    $this->setWithdrawPrivateClient($dto, $sum, 1);
                }
            } else {
                $sum = $dto->amount;
                $dto->amount = $this->calculateAmount($dto->amount, $dto->amount);

                $this->setWithdrawPrivateClient($dto, $sum, 1);
            }
        } else {
            $this->setWithdrawPrivateClient($dto, $dto->amount, 1);
        }

        return $dto;
    }

    private function isCurrentWeek(Carbon $previousDate, Carbon $currentDate): bool
    {
        $startDateOnWeek = $previousDate->startOfWeek();

        return $currentDate->diffInDays($startDateOnWeek) < self::DAYS_IN_WEEK;
    }

    private function calculateAmount(float $sum, float $amount): float
    {
        if ($sum > self::AMOUNT_FREE) {
            if (($sum - $amount) > self::AMOUNT_FREE) {
                return $amount;
            }

            return $sum - self::AMOUNT_FREE;
        }

        return 0;
    }

    private function setWithdrawPrivateClient(TransactionDTO $dto, float $sum, int $iteration = 1): void
    {
        static::$withdrawPrivateClient[] = [
            'client_id' => $dto->clientId,
            'client_type' => $dto->clientType,
            'operation_type' => $dto->operationType,
            'date' => $dto->date,
            'amount' => $dto->amount,
            'iteration_of_week' => $iteration,
            'sum' => $sum,
        ];
    }
}
