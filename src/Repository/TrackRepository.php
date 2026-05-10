<?php

namespace App\Repository;

use App\Entity\Track;

class TrackRepository
{
    private array $tracks;

    public function __construct()
    {
        $this->tracks = require __DIR__ . '/../../config/tracks.php';
    }

    public function getAll(): array
    {
        $tracks = [];

        foreach ($this->tracks as $trackData) {
            $tracks[] = new Track(
                $trackData['artist'],
                $trackData['title'],
                $trackData['price']
            );
        }

        return $tracks;
    }
    public function findByIndex(int $index): ?Track
    {
        $tracks = $this->getAll();

        return $tracks[$index] ?? null;
    }

    public function findByTitle(string $title): ?Track
    {
        foreach ($this->getAll() as $track) {
            if (strtolower($track->getTitle()) === strtolower($title)) {
                return $track;
            }
        }

        return null;
    }
}