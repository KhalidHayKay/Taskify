<?php

declare(strict_types=1);

namespace App\Controllers;

use App\DTOs\ContactPersonData;
use App\Services\ContactPersonProviderService;
use App\Validators\ContactPersonVaidator;
use App\Validators\ValidatorFactory;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Slim\Views\Twig;

class ContactPersonController
{
    public function __construct(
        private readonly Twig $twig,
        private readonly ContactPersonProviderService $contactPersonProvider,
        private readonly ValidatorFactory $validatorFactory,
    ) {}

    public function index(ServerRequestInterface $request, ResponseInterface $response): ResponseInterface
    {
        $contactPerson = $this->contactPersonProvider->get($request->getAttribute('user'));

        // var_dump($contactPerson);

        return $this->twig->render($response, 'contact_person/index.twig', [
            'fullname' => $contactPerson?->getFullname(),
            'email'    => $contactPerson?->getEmail(),
        ]);
    }

    public function create(ServerRequestInterface $request, ResponseInterface $response): ResponseInterface
    {
        $data = $this->validatorFactory->resolve(ContactPersonVaidator::class)->validate($request->getParsedBody());

        $this->contactPersonProvider->set(new ContactPersonData(
            $data['email'],
            $data['fullname'],
        ), $request->getAttribute('user'));

        return $response->withStatus(302)->withAddedHeader('Location', '/user/contact_person');
    }

    public function delete(ServerRequestInterface $request, ResponseInterface $response): ResponseInterface
    {
        $user = $request->getAttribute('user');

        $this->contactPersonProvider->remove($user);

        return $response->withStatus(302)->withAddedHeader('Location', '/user/contact_person');
    }

    public function edit(ServerRequestInterface $request, ResponseInterface $response): ResponseInterface
    {
        $data = $this->validatorFactory->resolve(ContactPersonVaidator::class)->validate($request->getParsedBody());

        $this->contactPersonProvider->update($request->getAttribute('user'), new ContactPersonData(
            $data['email'],
            $data['fullname']
        ));

        return $response->withStatus(302)->withAddedHeader('Location', '/user/contact_person');
    }
}