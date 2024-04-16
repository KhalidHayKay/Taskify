<?php

declare(strict_types=1);

namespace App\Middlewares;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Psr\Http\Message\ResponseFactoryInterface;
use App\Exceptions\InvalidCredentialsException;
use App\Interfaces\SessionInterface;
use App\ResponseFormatter;
use App\Services\RequestService;

class InvalidCredentialsExceptionMiddleware implements MiddlewareInterface
{
    public function __construct(
        private readonly ResponseFactoryInterface $responseFactory, 
        private readonly SessionInterface $session,
        private readonly RequestService $requestService,
        private readonly ResponseFormatter $responseFormatter
    )
    {
    }

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        try {
            return $handler->handle($request);
        } catch (InvalidCredentialsException $e) {
            $response = $this->responseFactory->createResponse();

            if ($this->requestService->isXHR($request)) {
                return $this->responseFormatter->asJson($response->withStatus(422), $e->errors);
            }

            $referer = $this->requestService->getReferer($request);
            $sensitiveData = ['password', 'confirm_password'];

            $oldCredentials = array_diff_key($request->getParsedBody(), array_flip($sensitiveData));

            $this->session->flash('validationErrors', $e->errors);
            $this->session->flash('oldFormData', $oldCredentials);

            return $response->withHeader('Location', $referer)->withStatus(302);
        }
    }
}