<?php

declare(strict_types=1);

namespace Tests\Traits;

use App\Jukebox\Jukebox;
use App\Repository\TrackRepositoryInterface;
use App\Service\AnalyticsClient;

use App\Service\ChangeCalculator;
use App\Service\CoinValidator;
use App\Service\PlaybackService;

trait JukeboxCreatorTrait
{
    protected function createJukebox(
        ?TrackRepositoryInterface $repository = null,
        ?PlaybackService $playbackService = null,
        ?AnalyticsClient$analyticsClient = null,
        ?CoinValidator $coinValidator = null,
        ?ChangeCalculator $changeCalculator = null,
    ): Jukebox {
        return new Jukebox(
            $repository ?? $this->createTrackRepositoryMock(),
            $coinValidator ?? new CoinValidator(),
            $changeCalculator ?? new ChangeCalculator(),
            $playbackService ?? $this->createPlaybackServiceMock(),
            $analyticsClient ?? $this->createAnalyticsClientMock(),
        );
    }

    protected function createTrackRepositoryMock(): TrackRepositoryInterface
    {
        return $this->createMock(TrackRepositoryInterface::class);
    }

    protected function createPlaybackServiceMock(): PlaybackService
    {
        return $this->createMock(PlaybackService::class);
    }

    protected function createAnalyticsClientMock(): AnalyticsClient
    {
        return $this->createMock(AnalyticsClient::class);
    }
}