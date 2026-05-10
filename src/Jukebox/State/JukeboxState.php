<?php

namespace App\Jukebox\State;

interface JukeboxState
{
    public function showTracks(): void;

    public function selectTrack(string $input): void;

    public function insertCoin(float $coin): void;

    public function play(): void;
}