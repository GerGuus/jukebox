<?php

declare(strict_types=1);

namespace Tests\Service;

use App\Service\CoinValidator;
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
        self::assertTrue($this->validator->isValid(0.01));
        self::assertTrue($this->validator->isValid(0.05));
        self::assertTrue($this->validator->isValid(0.10));
        self::assertTrue($this->validator->isValid(0.25));
        self::assertTrue($this->validator->isValid(0.50));
        self::assertTrue($this->validator->isValid(1.00));
    }

    public function testInvalidCoins(): void
    {
        self::assertFalse($this->validator->isValid(0.02));
        self::assertFalse($this->validator->isValid(0.15));
        self::assertFalse($this->validator->isValid(2.00));
    }
}