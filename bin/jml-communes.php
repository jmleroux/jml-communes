<?php

include_once __DIR__ . '/../vendor/autoload.php';

use Symfony\Component\Console\Application;

$app = new Application('My CLI Application', '0.1.0');

$app->addCommands([
    new JML\Communes\Command\DownloadCommand('foo'),
]);

$app->run();
