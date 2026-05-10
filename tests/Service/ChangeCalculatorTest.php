<?php

declare(strict_types=1);

namespace Tests\Service;

use App\Service\ChangeCalculator;
use PHPUnit\Framework\TestCase;

final class ChangeCalculatorTest extends TestCase
{
    private ChangeCalculator $calculator;

    protected function setUp(): void
    {
        $this->calculator = new ChangeCalculator();
    }

    public function testCalculatesChangeForEightyCents(): void
    {
        $result = $this->calculator->calculate(0.80);

        self::assertSame([0.50, 0.25, 0.05], $result);
    }

    public function testReturnsEmptyArrayForZeroChange(): void
    {
        $result = $this->calculator->calculate(0.0);

        self::assertSame([], $result);
    }
}