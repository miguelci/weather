#!/usr/bin/env php
<?php

declare(strict_types=1);

namespace Weather;

use Symfony\Component\Console\Application;
use Symfony\Component\Console\CommandLoader\CommandLoaderInterface;
use Symfony\Component\DependencyInjection\Container;

use function assert;

$builder = require __DIR__ . '/../config/container_builder.php';
assert($builder instanceof Container);

$commandLoader = $builder->get('console.command_loader');
assert($commandLoader instanceof CommandLoaderInterface);

$application = new Application('Generate Predictions');
$application->setCommandLoader($commandLoader);
$application->run();
