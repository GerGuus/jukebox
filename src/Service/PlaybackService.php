<?php

declare(strict_types=1);

namespace App\Service;

use Symfony\Component\Console\Helper\ProgressBar;
use Symfony\Component\Console\Output\ConsoleOutput;

class PlaybackService
{
    public function imitatePlaying(): void
    {
        $output = new ConsoleOutput();
        $progressBar = new ProgressBar($output, 50);
        $progressBar->setFormat('Playing [%bar%] %percent:3s%%');
        $progressBar->start();

        for ($i = 0; $i < 50; $i++) {
            usleep(200000);
            $progressBar->advance();
        }

        $progressBar->finish();
        $output->writeln('');
    }
}