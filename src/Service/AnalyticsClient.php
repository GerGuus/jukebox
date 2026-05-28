<?php

declare(strict_types=1);

namespace App\Service;

use App\Entity\Track;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Exception\ServerException;
use GuzzleHttp\Exception\TransferException;
use Psr\Log\LoggerInterface;

class AnalyticsClient
{
    public function __construct(
        private Client $httpClient,
        private LoggerInterface $logger,
        private string $baseUrl,
    ) {
    }

    public function sendTrackPlayed(Track $track, string $amountPaid): void
    {
        $maxAttempts = 2;

        for ($attempt = 1; $attempt <= $maxAttempts; $attempt++) {
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
            } catch (ClientException $e) {
                $this->logger->error('Failed to send analytics event', [
                    'error' => $e->getMessage(),
                    'track_id' => $track->getId(),
                    'title' => $track->getTitle(),
                    'amount_paid' => $amountPaid,
                    'status_code' => $e->getResponse()?->getStatusCode(),
                ]);

                return;
            } catch (ServerException | TransferException $e) {
                if ($attempt === $maxAttempts) {
                    $this->logger->error('Failed to send analytics event', [
                        'error' => $e->getMessage(),
                        'track_id' => $track->getId(),
                        'title' => $track->getTitle(),
                        'amount_paid' => $amountPaid,
                        'status_code' => $e->getResponse()?->getStatusCode(),
                    ]);

                    return;
                }

                usleep(200000);
            } catch (\Throwable $e) {
                if ($attempt === $maxAttempts) {
                    $this->logger->error('Failed to send analytics event', [
                        'error' => $e->getMessage(),
                        'track_id' => $track->getId(),
                        'title' => $track->getTitle(),
                        'amount_paid' => $amountPaid,
                    ]);

                    return;
                }

                usleep(200000);
            }
        }
    }
}