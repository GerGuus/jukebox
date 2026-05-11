<?php

declare(strict_types=1);

namespace App\Command;

use App\Jukebox\Jukebox;
use App\ValueObject\Money;
use InvalidArgumentException;

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

            while ($this->jukebox->getSelectedTrack() === null) {
                $trackInput = $this->readLine('Select track by number or title: ');

                $this->jukebox->selectTrack($trackInput);
            }

            while (true) {
                $selectedTrack = $this->jukebox->getSelectedTrack();

                $coinInput = $this->readLine("Insert coin (0.01, 0.05, 0.10, 0.25, 0.50, 1.00): ");

                try {
                    $coin = Money::createFromString($coinInput);
                } catch (InvalidArgumentException) {
                    echo "Invalid coin format\n";
                    continue;
                }

                $this->jukebox->insertCoin($coin);

                if ($this->jukebox->getInsertedAmount()->greaterThanOrEqual($selectedTrack->getPrice())) {
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