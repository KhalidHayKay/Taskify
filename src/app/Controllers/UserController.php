<?php

declare(strict_types=1);

namespace App\Controllers;

use App\DTOs\ContactPersonData;
use Slim\Views\Twig;
use App\Services\ContactPersonProviderService;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class UserController
{
    public function __construct(private readonly Twig $twig, private readonly ContactPersonProviderService $contactPersonProvider)
    {
    }

    public function index(ServerRequestInterface $request, ResponseInterface $response): ResponseInterface
    {
        $user = $request->getAttribute('user');
        $contactPerson = $this->contactPersonProvider->get($user);

        return  $this->twig->render($response, 'user/account.twig', [
            'user' => [
                'name' => $user->getUsername(),
                'fullname' => $user->getFullname(),
                'email' => $user->getEmail(),
                'dateOfBirth' => $user->getDateOfBirth(),
            ],
            'contactPerson' => [
                'email' => $contactPerson?->getEmail(),
                'fullname' => $contactPerson?->getFullname(),
            ]
        ]);
    }

    public function setContactPerson(ServerRequestInterface $request, ResponseInterface $response): ResponseInterface
    {
        $data = $request->getParsedBody();

        var_dump($data);

        $this->contactPersonProvider->crete(new ContactPersonData(
            $data['email'],
            $data['fullname']
        ));

        return $response;
    }
}