<?php

declare(strict_types=1);

namespace App\Middlewares;

use App\Interfaces\SessionInterface;
use Slim\Views\Twig;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

class ValidationErrorsMIddleware implements MiddlewareInterface
{
    public function __construct(private readonly Twig $twig, private readonly SessionInterface $session)
    {   
    }
    
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        if ($validationErrors = $this->session->getFlash('validationErrors')) {
            $this->twig->getEnvironment()->addGlobal('validationErrors', $validationErrors);
        }

        return $handler->handle($request);
    }
}