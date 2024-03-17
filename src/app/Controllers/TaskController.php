<?php

declare(strict_types=1);

namespace App\Controllers;

use Slim\Views\Twig;
use Doctrine\ORM\EntityManager;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

class TaskController
{
    public function __construct(private readonly Twig $twig, private readonly EntityManager $entityManager)
    {
    }

    public function index(RequestInterface $request, ResponseInterface $response): ResponseInterface
    {
        return $this->twig->render($response, 'task.twig');
    }
}