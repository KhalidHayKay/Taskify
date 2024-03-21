<?php

declare(strict_types=1);

namespace App\Middlewares;

use App\Interfaces\AuthInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Slim\Views\Twig;

class TwigGlobalVariablesMiddleware implements MiddlewareInterface
{
    public function __construct(private readonly Twig $twig, private readonly AuthInterface $auth)
    {
    }

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $username = $this->auth->user()?->getUsername();

        $this->twig->getEnvironment()->addGlobal('username', $username);
        
        return $handler->handle($request);
    }
}