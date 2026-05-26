<?php

use App\Repository\ConfigTrackRepository;
use App\Repository\TrackRepositoryInterface;
use App\Service\AnalyticsClient;
use GuzzleHttp\Client;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;
use Psr\Log\LoggerInterface;
use function DI\factory;

return [
    TrackRepositoryInterface::class => factory(static function () {
        return new ConfigTrackRepository(
            require __DIR__ . '/tracks.php'
        );
    }),

    Client::class => factory(static function () {
        return new Client([
            'timeout' => (float) ($_ENV['ANALYTICS_TIMEOUT'] ?? 2),
        ]);
    }),

    LoggerInterface::class => factory(static function () {
        $logger = new Logger('jukebox');
        $logger->pushHandler(new StreamHandler(__DIR__ . '/../var/log/jukebox.log'));

        return $logger;
    }),

    AnalyticsClient::class => factory(static function ($container) {
        return new AnalyticsClient(
            $container->get(Client::class),
            $container->get(LoggerInterface::class),
            $_ENV['ANALYTICS_BASE_URL'] ?? ''
        );
    }),
];