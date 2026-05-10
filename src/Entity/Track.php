<?php

namespace App\Entity;

class Track
{
    public function __construct(
        private string $artist,
        private string $title,
        private float $price
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

    public function getPrice(): float
    {
        return $this->price;
    }

    public function getDisplayName(): string
    {
        return $this->artist . ' — ' . $this->title;
    }
}