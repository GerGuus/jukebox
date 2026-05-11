<?php

declare(strict_types=1);

namespace App\Service;

use App\ValueObject\Money;

class CoinValidator
{
    private const ALLOWED_COINS = [1, 2, 5, 10, 25, 50, 100];

    public function isValid(Money $coin): bool
    {
        return in_array($coin->toCents(), self::ALLOWED_COINS, true);
    }
}