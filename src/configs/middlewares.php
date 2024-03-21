<?php

declare(strict_types=1);

use App\Middlewares\CsrfFieldsMiddleware;
use App\Middlewares\InvalidCredentialsExceptionMiddleware;
use App\Middlewares\OldFormDataMiddleware;
use App\Middlewares\SessionStartSessionMiddleware;
use App\Middlewares\TwigGlobalVariablesMiddleware;
use App\Middlewares\ValidationErrorsMIddleware;
use Psr\Container\ContainerInterface;
use Slim\App;
use Slim\Views\Twig;
use Slim\Views\TwigMiddleware;

return function(App $app, ContainerInterface $container) {
    $app->add(CsrfFieldsMiddleware::class);
    $app->add('csrf');
    $app->add(TwigMiddleware::create($app, $container->get(Twig::class)));
    $app->add(InvalidCredentialsExceptionMiddleware::class);
    $app->add(TwigGlobalVariablesMiddleware::class);
    $app->add(OldFormDataMiddleware::class);
    $app->add(ValidationErrorsMIddleware::class);
    $app->add(SessionStartSessionMiddleware::class);
};
