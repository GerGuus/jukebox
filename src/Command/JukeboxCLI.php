<?php

declare(strict_types=1);

namespace App\Command;

use App\Jukebox\Jukebox;
use App\ValueObject\Money;
use InvalidArgumentException;

class JukeboxCLI
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

                if ($this->isExitCommand($trackInput)) {
                    echo "Bye\n";
                    return;
                }

                $this->jukebox->selectTrack($trackInput);
            }

            while (true) {
                $selectedTrack = $this->jukebox->getSelectedTrack();

                if ($selectedTrack === null) {
                    break;
                }

                $coinInput = $this->readLine("Insert coin (0.01, 0.05, 0.10, 0.25, 0.50, 1.00): ");

                if ($this->isExitCommand($coinInput)) {
                    echo "Bye\n";
                    return;
                }

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

    private function isExitCommand(string $input): bool
    {
        $normalized = mb_strtolower(trim($input));

        return $normalized === 'q'
            || $normalized === 'quit'
            || $normalized === 'exit';
    }
}