<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Mail\TestMail;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class MailController
{
    public function __construct(private readonly TestMail $testMail)
    {
        
    }
    public function test(ServerRequestInterface $request, ResponseInterface $response): ResponseInterface
    {
        $this->testMail->send($request->getAttribute('user'));

        return $response;
    }
}