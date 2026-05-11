<?php

declare(strict_types=1);

namespace Tests\Service;

use App\Service\ChangeCalculator;
use App\ValueObject\Money;
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
        $result = $this->calculator->calculate(Money::createFromCents(80));

        self::assertSame([50, 25, 5], $result);
    }

    public function testReturnsEmptyArrayForZeroChange(): void
    {
        $result = $this->calculator->calculate(Money::createWithZero());

        self::assertSame([], $result);
    }
}