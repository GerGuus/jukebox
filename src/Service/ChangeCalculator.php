<?php

declare(strict_types=1);

namespace App\Service;

use App\ValueObject\Money;

class ChangeCalculator
{
    private const COINS = [100, 50, 25, 10, 5, 1];

    public function calculate(Money $change): array
    {
        $remaining = $change->toCents();
        $result = [];

        foreach (self::COINS as $coin) {
            while ($remaining >= $coin) {
                $result[] = $coin / 100;

                $remaining -= $coin;
            }
        }

        return $result;
    }
}