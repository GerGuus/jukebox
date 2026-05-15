<?php

use App\Repository\ConfigTrackRepository;
use App\Repository\TrackRepositoryInterface;
use function DI\factory;

return [
    TrackRepositoryInterface::class => factory(static function () {
        return new ConfigTrackRepository(
            require __DIR__ . '/tracks.php'
        );
    }),
];