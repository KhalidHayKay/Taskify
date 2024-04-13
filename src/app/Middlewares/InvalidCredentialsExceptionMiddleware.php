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

class InvalidCredentialsExceptionMiddleware implements MiddlewareInterface
{
    public function __construct(private readonly ResponseFactoryInterface $responseFactory, private readonly SessionInterface $session)
    {
    }

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        try {
            return $handler->handle($request);
        } catch (InvalidCredentialsException $e) {
            $response = $this->responseFactory->createResponse();

            if ($request->getHeaderLine('X-Requested-With') === 'XMLHttpRequest') {
                var_dump($e->errors);
                return $response->withStatus(422);
            }

            $referer = $request->getServerParams()['HTTP_REFERER'];
            $sensitiveData = ['password', 'confirm_password'];

            $oldCredentials = array_diff_key($request->getParsedBody(), array_flip($sensitiveData));

            $this->session->flash('validationErrors', $e->errors);
            $this->session->flash('oldFormData', $oldCredentials);

            return $response->withHeader('Location', $referer)->withStatus(302);
        }
    }
}