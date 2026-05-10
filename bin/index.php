<?php

declare(strict_types=1);

require __DIR__ . '/../vendor/autoload.php';

use App\Command\JukeboxCommand;
use DI\ContainerBuilder;

$containerBuilder = new ContainerBuilder();
$container = $containerBuilder->build();

$command = $container->get(JukeboxCommand::class);
$command->run();