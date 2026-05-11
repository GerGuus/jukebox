<?php

declare(strict_types=1);

namespace Tests\Jukebox\State;

use App\Entity\Track;
use App\Jukebox\Jukebox;
use App\Jukebox\State\WaitingForPaymentState;
use App\Repository\TrackRepository;
use App\Repository\TrackRepositoryInterface;
use App\Service\ChangeCalculator;
use App\Service\CoinValidator;
use App\ValueObject\Money;
use PHPUnit\Framework\TestCase;
use Tests\Helpers\OutputCaptureHelper;

final class WaitingForPaymentStateTest extends TestCase
{
    public function testInvalidCoin(): void
    {
        $jukebox = new Jukebox(
            $this->createMock(TrackRepositoryInterface::class),
            new CoinValidator(),
            new ChangeCalculator()
        );
        $jukebox->setSelectedTrack(new Track('Oomph', 'Labyrinth', Money::createFromCents(150)));

        $state = new WaitingForPaymentState($jukebox);

        $output = OutputCaptureHelper::captureOutput(static function () use ($state): void {
            $state->insertCoin(Money::createFromCents(15));
        });

        self::assertStringContainsString('Coin has not been accepted', $output);
        self::assertEquals(0.0, $jukebox->getInsertedAmount()->toCents());
    }

    public function testAddsInsertedAmountForValidCoin(): void
    {
        $jukebox = new Jukebox(
            $this->createMock(TrackRepositoryInterface::class),
            new CoinValidator(),
            new ChangeCalculator()
        );
        $jukebox->setSelectedTrack(new Track('Oomph', 'Labyrinth', Money::createFromCents(150)));

        $state = new WaitingForPaymentState($jukebox);

        $state->insertCoin(Money::createFromCents(25));

        self::assertEquals(25, $jukebox->getInsertedAmount()->toCents());
    }

    public function testPrintsRemainingAmountWhenNotEnoughMoney(): void
    {
        $jukebox = new Jukebox(
            $this->createMock(TrackRepositoryInterface::class),
            new CoinValidator(),
            new ChangeCalculator()
        );
        $jukebox->setSelectedTrack(new Track('Oomph', 'Labyrinth', Money::createFromCents(150)));

        $state = new WaitingForPaymentState($jukebox);

        $output = OutputCaptureHelper::captureOutput(static function () use ($state): void {
            $state->insertCoin(Money::createFromCents(25));
        });

        self::assertStringContainsString('Remaining:', $output);
    }
}