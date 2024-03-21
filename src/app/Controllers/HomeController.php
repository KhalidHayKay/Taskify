<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Auth;
use Slim\Views\Twig;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseFactoryInterface;

class HomeController
{
    public function __construct(private readonly Twig $twig, private readonly Auth $auth)
    {
    }

    public function index(ServerRequestInterface $request, ResponseInterface $response): ResponseInterface
    {
        return $this->twig->render($response, 'dashboard.twig');
    }
}