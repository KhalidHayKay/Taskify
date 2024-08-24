<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Entity\User;
use Slim\Views\Twig;
use App\DTOs\ContactPersonData;
use App\Validators\ValidatorFactory;
use App\Services\UserProviderService;
use App\Mail\ContactPersonRequestMail;
use App\ResponseFormatter;
use Psr\Http\Message\ResponseInterface;
use App\Validators\ContactPersonVaidator;
use Psr\Http\Message\ServerRequestInterface;
use App\Services\ContactPersonProviderService;

class ContactPersonController
{
    public function __construct(
        private readonly Twig $twig,
        private readonly ContactPersonProviderService $contactPersonProvider,
        private readonly UserProviderService $userProvider,
        private readonly ValidatorFactory $validatorFactory,
        private readonly ContactPersonRequestMail $contactPersonRequestMail,
        private readonly ResponseFormatter $responseFormatter,
    ) {}

    public function index(ServerRequestInterface $request, ResponseInterface $response): ResponseInterface
    {
        $user          = $request->getAttribute('user');
        $contactPerson = $this->contactPersonProvider->get($user);

        if ($contactPerson && ! $contactPerson?->getHasAccepted()) {
            if (
                array_key_exists('request', $request->getQueryParams()) &&
                $request->getQueryParams()['request'] === 'sent'
            ) {
                return $this->twig->render($response, 'contactPerson/message.twig', [
                    'message' => "Your request has been sent to " . $contactPerson->getFullname() . " Via provided email.",
                    'button'  => true,
                ]);
            }

            return $this->twig->render($response, 'contactPerson/pending.twig', [
                'fullname' => $contactPerson?->getFullname(),
            ]);
        }

        if (! $contactPerson) {
            return $response->withHeader('Location', '/user/contact_person/create');
        }

        return $this->twig->render($response, 'contactPerson/index.twig', [
            'fullname' => $contactPerson?->getFullname(),
            'email'    => $contactPerson?->getEmail(),
        ]);
    }

    public function createView(ServerRequestInterface $request, ResponseInterface $response): ResponseInterface
    {
        /**
         * @var User $user
         */
        $user = $request->getAttribute('user');

        if ($user->getContactPerson() && $user->getContactPerson()->getHasAccepted()) {
            return $response->withHeader('Location', '/user/contact_person');
        }

        return $this->twig->render($response, 'contactPerson/create.twig');
    }

    public function create(ServerRequestInterface $request, ResponseInterface $response): ResponseInterface
    {
        /**
         * @var User $user
         */
        $user = $request->getAttribute('user');

        $data = $this->validatorFactory->resolve(ContactPersonVaidator::class)->validate($request->getParsedBody());

        if ($user->getContactPerson()) {
            $this->contactPersonProvider->remove($user);
        }

        $contactPerson = $this->contactPersonProvider->set(new ContactPersonData(
            $data['email'],
            $data['fullname'],
        ), $request->getAttribute('user'));

        $this->contactPersonRequestMail->send($user, $contactPerson);

        return $response->withStatus(302)->withAddedHeader('Location', '/user/contact_person?request=sent');
    }

    public function delete(ServerRequestInterface $request, ResponseInterface $response): ResponseInterface
    {
        $user = $request->getAttribute('user');

        $this->contactPersonProvider->remove($user);

        return $response->withStatus(302)->withAddedHeader('Location', '/user/contact_person');
    }

    public function request(ServerRequestInterface $request, ResponseInterface $response): ResponseInterface
    {
        $uri = (string) $request->getUri();

        //todo: See if there is a better way to retrieve user id;
        $userId = (int) explode('/', $uri)[5];
        $user   = $this->userProvider->findById($userId);

        $this->contactPersonProvider->accept($user);

        return $this->twig->render($response, 'contactPerson/message.twig', [
            'message' => "You have accepted the request to be " . $user->getFullname() . "'s Contact Person.",
        ]);
    }

    public function checkAcknowledgement(ServerRequestInterface $request, ResponseInterface $response): ResponseInterface
    {
        $acknowledgement = $this->contactPersonProvider->checkAcknowledgement($request->getAttribute("user"));

        return $this->responseFormatter->asJson($response, $acknowledgement);
    }

    public function acknowledge(ServerRequestInterface $request, ResponseInterface $response): ResponseInterface
    {
        $this->contactPersonProvider->setAcknowledgement($request->getAttribute('user'));

        return $response;
    }
}