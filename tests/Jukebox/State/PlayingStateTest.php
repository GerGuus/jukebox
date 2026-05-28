<?php

declare(strict_types=1);

namespace Tests\Jukebox\State;

use App\Entity\Track;
use App\Jukebox\State\PlayingState;
use App\Service\AnalyticsClient;
use App\Service\PlaybackService;
use App\ValueObject\Money;
use PHPUnit\Framework\TestCase;
use Tests\Helpers\OutputCaptureHelper;
use Tests\Traits\JukeboxCreatorTrait;

final class PlayingStateTest extends TestCase
{
    use JukeboxCreatorTrait;

    public function testPlayPrintsTrackName(): void
    {
        $track = new Track(1, 'Oomph', 'Labyrinth', Money::createFromCents(150));

        $playbackService = $this->createMock(PlaybackService::class);
        $playbackService
            ->expects(self::once())
            ->method('imitatePlaying');

        $analyticsClient = $this->createStub(AnalyticsClient::class);

        $jukebox = $this->createJukebox(
            playbackService: $playbackService,
            analyticsClient: $analyticsClient,
        );

        $jukebox->setSelectedTrack($track);
        $jukebox->addInsertedAmount(Money::createFromCents(150));

        $state = new PlayingState($jukebox);

        $output = OutputCaptureHelper::captureOutput(static function () use ($state): void {
            $state->play();
        });

        self::assertStringContainsString('Now playing: Oomph — Labyrinth', $output);
    }

    public function testPlayPrintsChangeWhenNeeded(): void
    {
        $track = new Track(1, 'Oomph', 'Labyrinth', Money::createFromCents(150));

        $playbackService = $this->createMock(PlaybackService::class);
        $playbackService
            ->expects(self::once())
            ->method('imitatePlaying');

        $analyticsClient = $this->createStub(AnalyticsClient::class);

        $jukebox = $this->createJukebox(
            playbackService: $playbackService,
            analyticsClient: $analyticsClient,
        );

        $jukebox->setSelectedTrack($track);
        $jukebox->addInsertedAmount(Money::createFromCents(200));

        $state = new PlayingState($jukebox);

        $output = OutputCaptureHelper::captureOutput(static function () use ($state): void {
            $state->play();
        });

        self::assertStringContainsString('Change:', $output);
        self::assertStringContainsString('Now playing: Oomph — Labyrinth', $output);
    }

    public function testPlayResetsJukeboxAfterPlayback(): void
    {
        $track = new Track(1, 'Oomph', 'Labyrinth', Money::createFromCents(150));

        $playbackService = $this->createMock(PlaybackService::class);
        $playbackService
            ->expects(self::once())
            ->method('imitatePlaying');

        $analyticsClient = $this->createStub(AnalyticsClient::class);

        $jukebox = $this->createJukebox(
            playbackService: $playbackService,
            analyticsClient: $analyticsClient,
        );

        $jukebox->setSelectedTrack($track);
        $jukebox->addInsertedAmount(Money::createFromCents(200));

        $state = new PlayingState($jukebox);
        $state->play();

        self::assertNull($jukebox->getSelectedTrack());
        self::assertSame(0, $jukebox->getInsertedAmount()->toCents());
    }
}