<?php

declare(strict_types=1);

use Slim\App;
use App\Config;
use Slim\Views\Twig;
use Clockwork\Clockwork;
use Slim\Views\TwigMiddleware;
use App\Enums\AppEnvironmentEnum;
use Psr\Container\ContainerInterface;
use App\Middlewares\CsrfFieldsMiddleware;
use App\Middlewares\OldFormDataMiddleware;
use Slim\Middleware\BodyParsingMiddleware;
use App\Middlewares\SessionStartMiddleware;
use Slim\Middleware\MethodOverrideMiddleware;
use App\Middlewares\ValidationErrorsMIddleware;
use Clockwork\Support\Slim\ClockworkMiddleware;
use App\Middlewares\TwigGlobalVariablesMiddleware;
use App\Middlewares\InvalidCredentialsExceptionMiddleware;

return function (App $app, ContainerInterface $container) {
    $config = $container->get(Config::class);

    $app->add(MethodOverrideMiddleware::class);
    $app->add(CsrfFieldsMiddleware::class);
    $app->add('csrf');
    $app->add(TwigMiddleware::create($app, $container->get(Twig::class)));
    $app->add(InvalidCredentialsExceptionMiddleware::class);
    $app->add(OldFormDataMiddleware::class);
    $app->add(ValidationErrorsMIddleware::class);
    $app->add(SessionStartMiddleware::class);
    if (AppEnvironmentEnum::isDevelopment($config->get('app_environment'))) {
        $app->add(new ClockworkMiddleware($app, $container->get(Clockwork::class)));
    }
    $app->add(BodyParsingMiddleware::class);
};
