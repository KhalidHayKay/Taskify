<?php

declare(strict_types=1);

namespace App\Middlewares;

use Slim\Views\Twig;
use App\Interfaces\SessionInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

class OldFormDataMiddleware implements MiddlewareInterface
{
    public function __construct(private readonly Twig $twig, private readonly SessionInterface $session)
    {   
    }
    
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        if ($oldFormData = $this->session->getFlash('oldFormData')) {
            $this->twig->getEnvironment()->addGlobal('oldFormData', $oldFormData);
        }

        return $handler->handle($request);
    }
}