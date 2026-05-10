<?php

declare(strict_types=1);

namespace Tests\Jukebox\State;

use App\Entity\Track;
use App\Jukebox\Jukebox;
use App\Jukebox\State\IdleState;
use App\Repository\TrackRepository;
use App\Service\ChangeCalculator;
use App\Service\CoinValidator;
use PHPUnit\Framework\TestCase;
use Tests\Helpers\OutputCaptureHelper;

final class IdleStateTest extends TestCase
{
    public function testShowTracksPrintsTrackList(): void
    {
        $track1 = new Track('Oomph', 'Labyrinth', 1.50);
        $track2 = new Track('Oomph', 'Beim erster Mal tuts immer weh', 1.20);

        $repository = $this->createMock(TrackRepository::class);
        $repository->method('getAll')->willReturn([$track1, $track2]);

        $jukebox = new Jukebox($repository, new CoinValidator(), new ChangeCalculator());
        $state = new IdleState($jukebox);

        $output = OutputCaptureHelper::captureOutput(static function () use ($state): void {
            $state->showTracks();
        });

        self::assertStringContainsString('Oomph — Labyrinth', $output);
        self::assertStringContainsString('Oomph — Beim erster Mal tuts immer weh', $output);
    }

    public function testSelectTrackByNumberSetsSelectedTrack(): void
    {
        $track = new Track('Oomph', 'Beim erster Mal tuts immer weh', 1.20);

        $repository = $this->createMock(TrackRepository::class);
        $repository->method('findByIndex')->with(0)->willReturn($track);

        $jukebox = new Jukebox($repository, new CoinValidator(), new ChangeCalculator());
        $state = new IdleState($jukebox);

        $state->selectTrack('1');

        self::assertSame($track, $jukebox->getSelectedTrack());
    }

    public function testSelectTrackByTitleSetsSelectedTrack(): void
    {
        $track = new Track('Oomph', 'Beim erster Mal tuts immer weh', 1.20);

        $repository = $this->createMock(TrackRepository::class);
        $repository->method('findByTitle')->with('Beim erster Mal tuts immer weh')->willReturn($track);

        $jukebox = new Jukebox($repository, new CoinValidator(), new ChangeCalculator());
        $state = new IdleState($jukebox);

        $state->selectTrack('Beim erster Mal tuts immer weh');

        self::assertSame($track, $jukebox->getSelectedTrack());
    }

    public function testSelectTrackPrintsErrorWhenTrackNotFound(): void
    {
        $repository = $this->createMock(TrackRepository::class);
        $repository->method('findByTitle')->willReturn(null);

        $jukebox = new Jukebox($repository, new CoinValidator(), new ChangeCalculator());
        $state = new IdleState($jukebox);

        $output = OutputCaptureHelper::captureOutput(static function () use ($state): void {
            $state->selectTrack('Unknown track');
        });

        self::assertStringContainsString('Track not found', $output);
        self::assertNull($jukebox->getSelectedTrack());
    }
}