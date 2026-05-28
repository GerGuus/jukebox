<?php

declare(strict_types=1);

namespace Tests\Jukebox;

use App\Entity\Track;
use App\Jukebox\Jukebox;
use App\Jukebox\State\IdleState;
use App\Repository\TrackRepository;
use App\Repository\TrackRepositoryInterface;
use App\Service\ChangeCalculator;
use App\Service\CoinValidator;
use App\ValueObject\Money;
use PHPUnit\Framework\TestCase;
use Tests\Traits\JukeboxCreatorTrait;

final class JukeboxTest extends TestCase
{
    use JukeboxCreatorTrait;

    public function testStoreSelectedTrack(): void
    {
        $jukebox = $this->createJukebox();
        $track = new Track(1, 'Oomph', 'Beim erster Mal tuts immer weh', Money::createFromCents(120));

        $jukebox->setSelectedTrack($track);

        self::assertSame($track, $jukebox->getSelectedTrack());
    }

    public function testAddInsertedAmount(): void
    {
        $jukebox = $this->createJukebox();

        $jukebox->addInsertedAmount(Money::createFromCents(25));
        $jukebox->addInsertedAmount(Money::createFromCents(100));

        self::assertEquals(125, $jukebox->getInsertedAmount()->toCents());
    }

    public function testResetClearsSelectedTrackAndAmount(): void
    {
        $jukebox = $this->createJukebox();
        $track = new Track(1, 'Oomph', 'Beim erster Mal tuts immer weh', Money::createFromCents(120));

        $jukebox->setSelectedTrack($track);
        $jukebox->addInsertedAmount(Money::createFromCents(100));
        $jukebox->reset();

        self::assertNull($jukebox->getSelectedTrack());
        self::assertEquals(0.0, $jukebox->getInsertedAmount()->toCents());
    }
}