<?php

declare(strict_types=1);

require __DIR__ . '/../vendor/autoload.php';

use App\Command\JukeboxCLI;
use DI\ContainerBuilder;
use Dotenv\Dotenv;

$dotenv = Dotenv::createImmutable(__DIR__ . '/..');
$dotenv->safeLoad();

$containerBuilder = new ContainerBuilder();
$containerBuilder->addDefinitions(__DIR__ . '/../config/container.php');
$container = $containerBuilder->build();


$command = $container->get(JukeboxCLI::class);
$command->run();