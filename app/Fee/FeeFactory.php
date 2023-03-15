<?php

declare(strict_types=1);

namespace App\Fee;

use App\Fee\Interfaces\FeeInterface;
use App\Fee\Rules\Deposit;
use App\Fee\Rules\WithdrawBusiness;
use App\Fee\Rules\WithdrawPrivateFee;
use App\Fee\Rules\WithdrawPrivateFree;

class FeeFactory
{
    private const TYPE_OPERATION_DEPOSIT = 'deposit';
    private const TYPE_OPERATION_WITHDRAW = 'withdraw';
    private const TYPE_USER_PRIVATE = 'private';
    private const TYPE_USER_BUSINESS = 'business';
    public static function make(string $typeOperation, string $typeUser): FeeInterface
    {
        switch (true) {
            case self::isDeposit($typeOperation):
                return new Deposit();
            case self::isWithdrawBusiness($typeOperation, $typeUser):
                return new WithdrawBusiness();
            case self::isWithdrawPrivateFeeCase1($typeOperation, $typeUser):
                return new WithdrawPrivateFee();
            default:
                dd(__METHOD__);
        }
    }

    private static function isDeposit(string $typeOperation): bool
    {
        return $typeOperation === self::TYPE_OPERATION_DEPOSIT;
    }

    private static function isWithdrawBusiness(string $typeOperation, string $typeUser): bool
    {
        return $typeOperation === self::TYPE_OPERATION_WITHDRAW && $typeUser === self::TYPE_USER_BUSINESS;
    }

    private static function isWithdrawPrivateFeeCase1(string $typeOperation, string $typeUser): bool
    {
        return $typeOperation === self::TYPE_OPERATION_WITHDRAW
            && $typeUser === self::TYPE_USER_PRIVATE;
    }

    private static function isWithdrawPrivateFeeCase2(string $typeOperation, string $typeUser, int $iterationOfWeek): bool
    {
        return $typeOperation === self::TYPE_OPERATION_WITHDRAW
            && $typeUser === self::TYPE_USER_PRIVATE
            && $iterationOfWeek <= 3;
    }
}
