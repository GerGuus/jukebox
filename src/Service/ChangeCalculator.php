<?php

namespace App\Service;

class ChangeCalculator
{
    private const COINS = [
        1.00,
        0.50,
        0.25,
        0.10,
        0.05,
        0.01,
    ];

    public function calculate(float $change): array
    {
        $result = [];

        foreach (self::COINS as $coin) {
            while ($change >= $coin) {
                $result[] = $coin;

                $change = round($change - $coin, 2);
            }
        }

        return $result;
    }
}