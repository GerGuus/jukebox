<?php

declare(strict_types=1);

namespace Tests\Jukebox\State;

use App\Entity\Track;
use App\Jukebox\Jukebox;
use App\Jukebox\State\PlayingState;
use App\Repository\TrackRepository;
use App\Repository\TrackRepositoryInterface;
use App\Service\ChangeCalculator;
use App\Service\CoinValidator;
use App\Service\PlaybackService;
use App\ValueObject\Money;
use PHPUnit\Framework\TestCase;
use Tests\Helpers\OutputCaptureHelper;

final class PlayingStateTest extends TestCase
{
    public function testPlayPrintsTrackName(): void
    {
        $jukebox = new Jukebox(
            $this->createMock(TrackRepositoryInterface::class),
            new CoinValidator(),
            new ChangeCalculator()
        );

        $track = new Track('Oomph', 'Labyrinth', Money::createFromCents(150));

        $jukebox->setSelectedTrack($track);
        $jukebox->addInsertedAmount(Money::createFromCents(150));

        $playbackService = $this->createMock(PlaybackService::class);
        $playbackService
            ->expects(self::once())
            ->method('imitatePlaying');

        $state = new PlayingState($jukebox, $playbackService);

        $output = OutputCaptureHelper::captureOutput(static function () use ($state): void {
            $state->play();
        });

        self::assertStringContainsString('Now playing: Oomph — Labyrinth', $output);
    }

    public function testPlayPrintsChangeWhenNeeded(): void
    {
        $jukebox = new Jukebox(
            $this->createMock(TrackRepositoryInterface::class),
            new CoinValidator(),
            new ChangeCalculator()
        );
        $track = new Track('Oomph', 'Labyrinth', Money::createFromCents(150));

        $jukebox->setSelectedTrack($track);
        $jukebox->addInsertedAmount(Money::createFromCents(200));

        $playbackService = $this->createMock(PlaybackService::class);
        $playbackService
            ->expects(self::once())
            ->method('imitatePlaying');

        $state = new PlayingState($jukebox, $playbackService);

        $output = OutputCaptureHelper::captureOutput(static function () use ($state): void {
            $state->play();
        });

        self::assertStringContainsString('Change:', $output);
    }

    public function testPlayResetsJukeboxAfterPlayback(): void
    {
        $jukebox = new Jukebox(
            $this->createMock(TrackRepositoryInterface::class),
            new CoinValidator(),
            new ChangeCalculator()
        );
        $track = new Track('Oomph', 'Labyrinth', Money::createFromCents(150));

        $jukebox->setSelectedTrack($track);
        $jukebox->addInsertedAmount(Money::createFromCents(200));

        $playbackService = $this->createMock(PlaybackService::class);
        $playbackService
            ->expects(self::once())
            ->method('imitatePlaying');

        $state = new PlayingState($jukebox, $playbackService);
        $state->play();

        self::assertNull($jukebox->getSelectedTrack());
        self::assertEquals(0.0, $jukebox->getInsertedAmount()->toCents());
    }
}