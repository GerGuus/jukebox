<?php

declare(strict_types=1);

namespace Tests\Service;

use App\Service\CoinValidator;
use App\ValueObject\Money;
use PHPUnit\Framework\TestCase;

final class CoinValidatorTest extends TestCase
{
    private CoinValidator $validator;

    protected function setUp(): void
    {
        $this->validator = new CoinValidator();
    }

    public function testAllowedCoins(): void
    {
        self::assertTrue($this->validator->isValid(Money::createFromCents(1)));
        self::assertTrue($this->validator->isValid(Money::createFromCents(5)));
        self::assertTrue($this->validator->isValid(Money::createFromCents(10)));
        self::assertTrue($this->validator->isValid(Money::createFromCents(25)));
        self::assertTrue($this->validator->isValid(Money::createFromCents(50)));
        self::assertTrue($this->validator->isValid(Money::createFromCents(100)));
    }

    public function testInvalidCoins(): void
    {
        self::assertFalse($this->validator->isValid(Money::createFromCents(7)));
        self::assertFalse($this->validator->isValid(Money::createFromCents(15)));
        self::assertFalse($this->validator->isValid(Money::createFromCents(75)));
    }
}