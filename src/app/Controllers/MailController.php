<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Mail\TestMail;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Slim\Views\Twig;

class MailController
{
    public function __construct(private readonly TestMail $testMail, private readonly Twig $twig) {}

    public function view(ServerRequestInterface $request, ResponseInterface $response): ResponseInterface
    {
        return $this->twig->render($response, 'mail/contact_person_request.twig', ['cp_fullname' => 'John Doe', 'fullname' => 'Jane Dane']);
    }

    public function viewTest(ServerRequestInterface $request, ResponseInterface $response): ResponseInterface
    {
        return $this->twig->render($response, 'mail/test.twig', ['fullname' => 'John Doe']);
    }

    public function test(ServerRequestInterface $request, ResponseInterface $response): ResponseInterface
    {
        $this->testMail->send($request->getAttribute('user'), 86);

        return $response;
    }
}