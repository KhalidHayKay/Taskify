<?php

declare(strict_types=1);

use Slim\App;
use Slim\Views\Twig;
use Slim\Views\TwigMiddleware;
use Psr\Container\ContainerInterface;
use App\Middlewares\CsrfFieldsMiddleware;
use App\Middlewares\OldFormDataMiddleware;
use Slim\Middleware\BodyParsingMiddleware;
use App\Middlewares\SessionStartMiddleware;
use App\Middlewares\ValidationErrorsMIddleware;
use App\Middlewares\TwigGlobalVariablesMiddleware;
use App\Middlewares\InvalidCredentialsExceptionMiddleware;

return function(App $app, ContainerInterface $container) {
    $app->add(CsrfFieldsMiddleware::class);
    $app->add('csrf');
    $app->add(TwigMiddleware::create($app, $container->get(Twig::class)));
    $app->add(InvalidCredentialsExceptionMiddleware::class);
    $app->add(OldFormDataMiddleware::class);
    $app->add(ValidationErrorsMIddleware::class);
    $app->add(SessionStartMiddleware::class);
    $app->add(BodyParsingMiddleware::class);
};
