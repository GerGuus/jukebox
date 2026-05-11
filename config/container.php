<?php

use App\Repository\ConfigTrackRepository;
use App\Repository\TrackRepositoryInterface;
use function DI\autowire;

return [
    TrackRepositoryInterface::class => autowire(ConfigTrackRepository::class),
];