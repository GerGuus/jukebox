<?php

declare(strict_types=1);

namespace Tests\Jukebox\State;

use App\Entity\Track;
use App\Jukebox\Jukebox;
use App\Jukebox\State\WaitingForPaymentState;
use App\Repository\TrackRepository;
use App\Service\ChangeCalculator;
use App\Service\CoinValidator;
use PHPUnit\Framework\TestCase;
use Tests\Helpers\OutputCaptureHelper;

final class WaitingForPaymentStateTest extends TestCase
{
    public function testInvalidCoin(): void
    {
        $jukebox = new Jukebox(new TrackRepository(), new CoinValidator(), new ChangeCalculator());
        $jukebox->setSelectedTrack(new Track('Oomph', 'Labyrinth', 1.50));

        $state = new WaitingForPaymentState($jukebox);

        $output = OutputCaptureHelper::captureOutput(static function () use ($state): void {
            $state->insertCoin(0.15);
        });

        self::assertStringContainsString('Coin has not been accepted', $output);
        self::assertEquals(0.0, $jukebox->getInsertedAmount());
    }

    public function testAddsInsertedAmountForValidCoin(): void
    {
        $jukebox = new Jukebox(new TrackRepository(), new CoinValidator(), new ChangeCalculator());
        $jukebox->setSelectedTrack(new Track('Oomph', 'Labyrinth', 1.50));

        $state = new WaitingForPaymentState($jukebox);

        $state->insertCoin(0.25);

        self::assertEquals(0.25, $jukebox->getInsertedAmount());
    }

    public function testPrintsRemainingAmountWhenNotEnoughMoney(): void
    {
        $jukebox = new Jukebox(new TrackRepository(), new CoinValidator(), new ChangeCalculator());
        $jukebox->setSelectedTrack(new Track('Oomph', 'Labyrinth', 1.50));

        $state = new WaitingForPaymentState($jukebox);

        $output = OutputCaptureHelper::captureOutput(static function () use ($state): void {
            $state->insertCoin(0.25);
        });

        self::assertStringContainsString('Remaining:', $output);
    }
}