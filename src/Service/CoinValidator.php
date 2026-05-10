<?php

namespace App\Service;

class CoinValidator
{
    private const ALLOWED_COINS = [
        0.01,
        0.05,
        0.10,
        0.25,
        0.50,
        1.00,
    ];

    public function isValid(float $coin): bool
    {
        return in_array($coin, self::ALLOWED_COINS, true);
    }
}