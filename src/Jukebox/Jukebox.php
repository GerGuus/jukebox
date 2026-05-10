<?php

namespace App\Jukebox;

use App\Entity\Track;
use App\Jukebox\State\IdleState;
use App\Jukebox\State\JukeboxState;
use App\Repository\TrackRepository;
use App\Service\ChangeCalculator;
use App\Service\CoinValidator;

class Jukebox
{
    private JukeboxState $currentState;
    private ?Track $selectedTrack = null;
    private float $insertedAmount = 0;

    public function __construct(
        private TrackRepository $trackRepository,
        private CoinValidator $coinValidator,
        private ChangeCalculator $changeCalculator,
    )
    {
        $this->currentState = new IdleState($this);
    }

    public function setState(JukeboxState $state): void
    {
        $this->currentState = $state;
    }

    public function showTracks(): void
    {
        $this->currentState->showTracks();
    }

    public function selectTrack(string $input): void
    {
        $this->currentState->selectTrack($input);
    }

    public function insertCoin(float $coin): void
    {
        $this->currentState->insertCoin($coin);
    }

    public function play(): void
    {
        $this->currentState->play();
    }

    public function getTrackRepository(): TrackRepository
    {
        return $this->trackRepository;
    }

    public function setSelectedTrack(Track $track): void
    {
        $this->selectedTrack = $track;
    }

    public function getSelectedTrack(): ?Track
    {
        return $this->selectedTrack;
    }

    public function getCoinValidator(): CoinValidator
    {
        return $this->coinValidator;
    }

    public function getChangeCalculator(): ChangeCalculator
    {
        return $this->changeCalculator;
    }

    public function addInsertedAmount(float $coin): void
    {
        $this->insertedAmount += $coin;
    }

    public function getInsertedAmount(): float
    {
        return $this->insertedAmount;
    }

    public function reset(): void
    {
        $this->selectedTrack = null;
        $this->insertedAmount = 0;
    }
}