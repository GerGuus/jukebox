<?php

namespace App\Jukebox\State;

use App\Entity\Track;
use App\Jukebox\Jukebox;

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

    public function insertCoin(float $coin): void
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

        echo 'Inserted: ' . $coin . "\n";
        echo 'Total: ' . $this->jukebox->getInsertedAmount() . "\n";

        if ($this->jukebox->getInsertedAmount() >= $track->getPrice()) {
            $this->jukebox->setState(new PlayingState($this->jukebox));
        } else {
            $remaining = $track->getPrice() - $this->jukebox->getInsertedAmount();
            echo 'Remaining: ' . $remaining . "\n";
        }
    }

    public function play(): void
    {
        echo "Insert coins first" . "\n";
    }
}