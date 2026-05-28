<?php

declare(strict_types=1);

namespace App\Service;

class PlaybackService
{
    public function imitatePlaying(): void
    {
        $steps = 20;

        for ($i = 0; $i <= $steps; $i++) {
            $percent = (int) (($i / $steps) * 100);
            $bar = str_repeat('#', $i) . str_repeat('-', $steps - $i);

            echo "\rPlaying [{$bar}] {$percent}%";

            usleep(200000);
        }

        echo PHP_EOL;
    }
}