<?php

declare(strict_types=1);

namespace App\Jukebox\State;

use App\Entity\Track;
use App\Jukebox\Jukebox;
use App\ValueObject\Money;

class WaitingForPaymentState implements JukeboxState
{
    public function __construct(
        private Jukebox $jukebox,
    ) {
    }

    public function showTracks(): void
    {
        echo "Track already selected" . "\n";
    }

    public function selectTrack(string $input): void
    {
        echo "Track already selected" . "\n";
    }

    public function insertCoin(Money $coin): void
    {
        $track = $this->jukebox->getSelectedTrack();

        if ($track === null) {
            echo "No track selected" . "\n";
            $this->jukebox->setState(new IdleState($this->jukebox));
            return;
        }

        if (!$this->jukebox->getCoinValidator()->isValid($coin)) {
            echo "Coin has not been accepted" . "\n";
            return;
        }

        $this->jukebox->addInsertedAmount($coin);

        if ($this->jukebox->getInsertedAmount()->greaterThanOrEqual($track->getPrice())) {
            $this->jukebox->setState(new PlayingState($this->jukebox));
        } else {
            $remaining = clone $track->getPrice();
            $remaining->subtract($this->jukebox->getInsertedAmount());
            echo 'Remaining: ' . $remaining . "\n";
        }
    }

    public function play(): void
    {
        echo "Insert coins first" . "\n";
    }
}