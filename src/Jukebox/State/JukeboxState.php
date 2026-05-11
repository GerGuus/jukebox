<?php

declare(strict_types=1);

namespace App\Jukebox\State;

use App\ValueObject\Money;

interface JukeboxState
{
    public function showTracks(): void;

    public function selectTrack(string $input): void;

    public function insertCoin(Money $coin): void;

    public function play(): void;
}