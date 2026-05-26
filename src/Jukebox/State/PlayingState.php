<?php

declare(strict_types=1);

namespace App\Jukebox\State;

use App\Jukebox\Jukebox;
use App\Service\PlaybackService;
use App\ValueObject\Money;

class PlayingState implements JukeboxState
{
    public function __construct(
        private Jukebox $jukebox,
    ) {
    }

    public function showTracks(): void
    {
        echo "Playback in progress" . "\n";
    }

    public function selectTrack(string $input): void
    {
        echo "Playback in progress" . "\n";
    }

    public function insertCoin(Money $coin): void
    {
        echo "Playback in progress" . "\n";
    }

    public function play(): void
    {
        $track = $this->jukebox->getSelectedTrack();

        if ($track === null) {
            echo "No track selected" . "\n";
            $this->jukebox->setState(new IdleState($this->jukebox));
            return;
        }

        $change = $this->jukebox->getInsertedAmount()->subtract($track->getPrice());

        if ($change->toCents() > 0) {
            $coins = $this->jukebox->getChangeCalculator()->calculate($change);

            echo "Change: " . implode(', ', $coins) . "\n";
        }

        $this->jukebox->getAnalyticsClient()->sendTrackPlayed(
            $track,
            $this->jukebox->getInsertedAmount()->format(),
        );

        echo "Now playing: " . $track->getDisplayName() . "\n";
        $this->jukebox->getPlaybackService()->imitatePlaying();

        $this->jukebox->reset();
        $this->jukebox->setState(new IdleState($this->jukebox));
    }
}