<?php

declare(strict_types=1);

namespace App\Middlewares;

use App\Exceptions\InvalidContactPersonDataException;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Psr\Http\Message\ResponseFactoryInterface;
use App\Interfaces\SessionInterface;
use App\ResponseFormatter;
use App\Services\RequestService;

class InvalidContactPersonDataExceptionMiddleware implements MiddlewareInterface
{
    public function __construct(
        private readonly ResponseFactoryInterface $responseFactory,
        private readonly SessionInterface $session,
        private readonly RequestService $requestService,
        private readonly ResponseFormatter $responseFormatter,
    ) {}

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        try {
            return $handler->handle($request);
        } catch (InvalidContactPersonDataException $e) {
            $response = $this->responseFactory->createResponse();

            $referer = $this->requestService->getReferer($request);

            $oldData = $request->getParsedBody();

            $this->session->flash('validationErrors', $e->errors);
            $this->session->flash('oldContactPersonData', $oldData);

            // var_dump($this->session->getFlash('validationErrors'), $this->session->getFlash('oldContactPersonData'));

            return $response->withHeader('Location', $referer)->withStatus(302);
        }
    }
}