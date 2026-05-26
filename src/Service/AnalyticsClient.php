<?php

declare(strict_types=1);

namespace App\Service;

use App\Entity\Track;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Psr\Log\LoggerInterface;

final class AnalyticsClient
{
    public function __construct(
        private Client $httpClient,
        private LoggerInterface $logger,
        private string $baseUrl,
    ) {
    }

    public function sendTrackPlayed(Track $track, string $amountPaid): void
    {
        for ($attempt = 1; $attempt <= 2; $attempt++) {
            try {
                $this->httpClient->post(
                    rtrim($this->baseUrl, '/') . '/api/logs',
                    [
                        'json' => [
                            'track_id' => $track->getId(),
                            'amount_paid' => $amountPaid,
                        ],
                    ]
                );

                return;
            } catch (GuzzleException |\Throwable $e) {
                if ($attempt === 2) {
                    $this->logger->error('Failed to send analytics event', [
                        'error' => $e->getMessage(),
                        'track_id' => $track->getId(),
                        'title' => $track->getTitle(),
                        'amount_paid' => $amountPaid,
                    ]);
                }

                usleep(200000);
            }
        }
    }
}