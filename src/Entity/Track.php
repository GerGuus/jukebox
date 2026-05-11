<?php

declare(strict_types=1);

namespace App\Entity;

use App\ValueObject\Money;

class Track
{
    public function __construct(
        private string $artist,
        private string $title,
        private Money $price,
    ) {
    }

    public function getArtist(): string
    {
        return $this->artist;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function getPrice(): Money
    {
        return $this->price;
    }

    public function getDisplayName(): string
    {
        return $this->artist . ' — ' . $this->title;
    }
}