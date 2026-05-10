<?php

namespace App\Command;

use App\Jukebox\Jukebox;
use App\Jukebox\State\IdleState;

class JukeboxCommand
{
    public function __construct(
        private Jukebox $jukebox,
    ) {
    }

    public function run(): void
    {
        while (true) {
            $this->jukebox->showTracks();

            $trackInput = $this->readLine('Select track by number or title: ');

            $this->jukebox->selectTrack($trackInput);

            while (true) {
                $selectedTrack = $this->jukebox->getSelectedTrack();

                $coin = $this->readLine("Insert coin (0.01, 0.05, 0.10, 0.25, 0.50, 1.00): ");

                $this->jukebox->insertCoin($coin);

                if ($this->jukebox->getInsertedAmount() >= $selectedTrack->getPrice()) {
                    $this->jukebox->play();
                    break;
                }
            }
        }
    }

    private function readLine(string $prompt): string
    {
        echo $prompt;

        $line = fgets(STDIN);

        return $line === false ? '' : trim($line);
    }
}