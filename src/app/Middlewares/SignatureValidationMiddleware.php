<?php

declare(strict_types=1);

namespace App\Middlewares;

use App\Config;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

class SignatureValidationMiddleware implements MiddlewareInterface
{
    public function __construct(private readonly Config $config) {}

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $uri               = $request->getUri();
        $queryParams       = $request->getQueryParams();
        $originalSignature = $queryParams['signature'];
        $expiration        = $queryParams['expiration'];

        unset($queryParams['signature']);

        $url = (string) $uri->withQuery(http_build_query($queryParams));

        $signature = hash_hmac('sha256', $url, $this->config->get('app_key'));

        if ($expiration <= time()) {
            throw new \RuntimeException('Link is expired');
        }

        if (! hash_equals($signature, $originalSignature)) {
            throw new \RuntimeException('Error Processing the request');
        }

        return $handler->handle($request);
    }
}