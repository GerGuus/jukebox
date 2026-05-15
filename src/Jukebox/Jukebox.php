<?php

declare(strict_types=1);

namespace App\Jukebox;

use App\Entity\Track;
use App\Jukebox\State\IdleState;
use App\Jukebox\State\JukeboxState;
use App\Repository\TrackRepositoryInterface;
use App\Service\ChangeCalculator;
use App\Service\CoinValidator;
use App\ValueObject\Money;

class Jukebox
{
    private JukeboxState $currentState;
    private ?Track $selectedTrack = null;
    private Money $insertedAmount;

    public function __construct(
        private TrackRepositoryInterface $trackRepository,
        private CoinValidator $coinValidator,
        private ChangeCalculator $changeCalculator,
    )
    {
        $this->currentState = new IdleState($this);
        $this->insertedAmount = Money::createWithZero();
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

    public function insertCoin(Money $coin): void
    {
        $this->currentState->insertCoin($coin);
    }

    public function play(): void
    {
        $this->currentState->play();
    }

    public function getTrackRepository(): TrackRepositoryInterface
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

    public function addInsertedAmount(Money $coin): void
    {
        $this->insertedAmount = $this->insertedAmount->add($coin);
    }

    public function getInsertedAmount(): Money
    {
        return $this->insertedAmount;
    }

    public function reset(): void
    {
        $this->selectedTrack = null;
        $this->insertedAmount = Money::createWithZero();
    }
}