<?php

declare(strict_types=1);

use Psr\Container\ContainerInterface;
use Slim\App;

/**
 * @var ContainerInterface
 */
$container = require __DIR__ . '/../boostrap.php';

$container->get(App::class)->run();