<?php

declare(strict_types=1);

namespace App\ValueObject;

use InvalidArgumentException;

final class Money
{
    public function __construct(
        private int $amount,
    ) {
        if ($amount < 0) {
            throw new InvalidArgumentException('Money amount cannot be negative');
        }
    }

    public static function createWithZero(): self
    {
        return new self(0);
    }

    public static function createFromCents(int $amount): self
    {
        return new self($amount);
    }

    public static function createFromString(string $amount): self
    {
        $normalized = str_replace(',', '.', trim($amount));

        if (!preg_match('/^\d+(\.\d{1,2})?$/', $normalized)) {
            throw new InvalidArgumentException('Invalid money format');
        }

        return new self((int) round((float) $normalized * 100));
    }

    public function add(self $other): Money
    {
        return new self($this->amount + $other->toCents());
    }

    public function subtract(self $other): Money
    {
        if ($other->amount > $this->amount) {
            throw new InvalidArgumentException('Result cannot be negative');
        }

        return new self($this->amount - $other->amount);
    }

    public function greaterThanOrEqual(self $other): bool
    {
        return $this->amount >= $other->amount;
    }

    public function equals(self $other): bool
    {
        return $this->amount === $other->amount;
    }

    public function toCents(): int
    {
        return $this->amount;
    }

    public function format(): string
    {
        return number_format($this->amount / 100, 2, '.', '');
    }

    public function __toString(): string
    {
        return $this->format();
    }
}