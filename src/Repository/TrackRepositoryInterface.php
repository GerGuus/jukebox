<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\Track;

interface TrackRepositoryInterface
{
    public function getAll(): array;

    public function findByIndex(int $index): ?Track;

    public function findByTitle(string $title): ?Track;
}