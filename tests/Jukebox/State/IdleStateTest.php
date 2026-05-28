<?php

declare(strict_types=1);

namespace Tests\Jukebox\State;

use App\Entity\Track;
use App\Jukebox\State\IdleState;
use App\Repository\TrackRepositoryInterface;
use App\ValueObject\Money;
use PHPUnit\Framework\TestCase;
use Tests\Helpers\OutputCaptureHelper;
use Tests\Traits\JukeboxCreatorTrait;

final class IdleStateTest extends TestCase
{
    use JukeboxCreatorTrait;

    public function testShowTracksPrintsTrackList(): void
    {
        $track1 = new Track(1, 'Oomph', 'Labyrinth', Money::createFromCents(150));
        $track2 = new Track(1, 'Oomph', 'Beim erster Mal tuts immer weh', Money::createFromCents(120));

        $repository = $this->createMock(TrackRepositoryInterface::class);
        $repository->method('getAll')->willReturn([$track1, $track2]);

        $jukebox = $this->createJukebox($repository);
        $state = new IdleState($jukebox);

        $output = OutputCaptureHelper::captureOutput(static function () use ($state): void {
            $state->showTracks();
        });

        self::assertStringContainsString('Oomph — Labyrinth', $output);
        self::assertStringContainsString('Oomph — Beim erster Mal tuts immer weh', $output);
    }

    public function testSelectTrackByNumberSetsSelectedTrack(): void
    {
        $track = new Track(1, 'Oomph', 'Beim erster Mal tuts immer weh', Money::createFromCents(120));

        $repository = $this->createMock(TrackRepositoryInterface::class);
        $repository->method('findByIndex')->with(0)->willReturn($track);

        $jukebox = $this->createJukebox($repository);
        $state = new IdleState($jukebox);

        $state->selectTrack('1');

        self::assertSame($track, $jukebox->getSelectedTrack());
    }

    public function testSelectTrackByTitleSetsSelectedTrack(): void
    {
        $track = new Track(1, 'Oomph', 'Beim erster Mal tuts immer weh', Money::createFromCents(120));

        $repository = $this->createMock(TrackRepositoryInterface::class);
        $repository->method('findByTitle')->with('Beim erster Mal tuts immer weh')->willReturn($track);

        $jukebox = $this->createJukebox($repository);
        $state = new IdleState($jukebox);

        $state->selectTrack('Beim erster Mal tuts immer weh');

        self::assertSame($track, $jukebox->getSelectedTrack());
    }

    public function testSelectTrackPrintsErrorWhenTrackNotFound(): void
    {
        $repository = $this->createMock(TrackRepositoryInterface::class);
        $repository->method('findByTitle')->willReturn(null);

        $jukebox = $this->createJukebox($repository);
        $state = new IdleState($jukebox);

        $output = OutputCaptureHelper::captureOutput(static function () use ($state): void {
            $state->selectTrack('Unknown track');
        });

        self::assertStringContainsString('Track not found', $output);
        self::assertNull($jukebox->getSelectedTrack());
    }
}