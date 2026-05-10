<?php

namespace App\Jukebox\State;

use App\Repository\TrackRepository;
use App\Jukebox\Jukebox;

class IdleState implements JukeboxState
{
    public function __construct(
        private Jukebox $jukebox,
    ) {
    }

    public function showTracks(): void
    {
        $tracks = $this->jukebox->getTrackRepository()->getAll();

        echo "Available tracks:" . "\n";

        foreach ($tracks as $index => $track) {
            $price = $track->getPrice();
            echo ($index + 1) . ". " . $track->getDisplayName() . " (" . $price . ")\n";
        }
    }

    public function selectTrack(string $input): void
    {
        $repository = $this->jukebox->getTrackRepository();

        $track = is_numeric($input)
            ? $repository->findByIndex((int) $input - 1)
            : $repository->findByTitle($input);

        if ($track === null) {
            echo "Track not found\n";
            return;
        }

        $this->jukebox->setSelectedTrack($track);
        $this->jukebox->setState(new WaitingForPaymentState($this->jukebox));

        echo "Selected: " . $track->getDisplayName() . "\n";
        echo 'Price: ' . $track->getPrice() . "\n";
    }


    public function insertCoin(float $coin): void
    {
        echo "Select track first\n";
    }

    public function play(): void
    {
        echo "Select track first\n";
    }
}