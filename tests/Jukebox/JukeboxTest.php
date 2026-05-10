<?php

declare(strict_types=1);

namespace Tests\Jukebox;

use App\Entity\Track;
use App\Jukebox\Jukebox;
use App\Jukebox\State\IdleState;
use App\Repository\TrackRepository;
use App\Service\ChangeCalculator;
use App\Service\CoinValidator;
use PHPUnit\Framework\TestCase;

final class JukeboxTest extends TestCase
{
    public function testStoreSelectedTrack(): void
    {
        $jukebox = $this->createJukebox();
        $track = new Track('Oomph', 'Beim erster Mal tuts immer weh', 1.20);

        $jukebox->setSelectedTrack($track);

        self::assertSame($track, $jukebox->getSelectedTrack());
    }

    public function testAddInsertedAmount(): void
    {
        $jukebox = $this->createJukebox();

        $jukebox->addInsertedAmount(0.25);
        $jukebox->addInsertedAmount(1.00);

        self::assertEquals(1.25, $jukebox->getInsertedAmount());
    }

    public function testResetClearsSelectedTrackAndAmount(): void
    {
        $jukebox = $this->createJukebox();
        $track = new Track('Oomph', 'Beim erster Mal tuts immer weh', 1.20);

        $jukebox->setSelectedTrack($track);
        $jukebox->addInsertedAmount(1.00);
        $jukebox->reset();

        self::assertNull($jukebox->getSelectedTrack());
        self::assertEquals(0.0, $jukebox->getInsertedAmount());
    }

    private function createJukebox(): Jukebox
    {
        $repository = new TrackRepository();
        $coinValidator = new CoinValidator();
        $changeCalculator = new ChangeCalculator();

        return new Jukebox($repository, $coinValidator, $changeCalculator);
    }
}