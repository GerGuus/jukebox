<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\Track;
use App\ValueObject\Money;

class ConfigTrackRepository implements TrackRepositoryInterface
{
    public function __construct(
        private array $tracks,
    ) {
    }

    public function getAll(): array
    {
        $tracks = [];

        foreach ($this->tracks as $trackData) {
            $tracks[] = $this->mapToTrack($trackData);
        }

        return $tracks;
    }

    public function findByIndex(int $index): ?Track
    {
        return isset($this->tracks[$index])
            ? $this->mapToTrack($this->tracks[$index])
            : null;
    }

    public function findByTitle(string $title): ?Track
    {
        foreach ($this->tracks as $track) {
            if (mb_strtolower($track['title']) === mb_strtolower($title)) {
                return $this->mapToTrack($track);
            }
        }

        return null;
    }

    private function mapToTrack(array $trackData): Track
    {
        return new Track(
            $trackData['artist'],
            $trackData['title'],
            is_int($trackData['price'])
                ? Money::createFromCents($trackData['price'])
                : Money::createFromString((string) $trackData['price'])
        );
    }
}