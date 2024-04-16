<?php

declare(strict_types=1);

namespace App\Services;

use App\Interfaces\SessionInterface;
use App\Session;
use Psr\Http\Message\ServerRequestInterface;

class RequestService
{
    public function __construct(private readonly SessionInterface $session)
    {
    }

    public function getReferer(ServerRequestInterface $request): string
    {
        $referer = $request->getHeader('referer')[0] ?? '';

        if (! $referer) {
            $this->session->put('check', $this->session->get('previousUrl'));
            return $this->session->get('previousUrl');
        }

        return $referer;
    }

    public function isXHR(ServerRequestInterface $request): bool
    {
        return $request->getHeaderLine('X-Requested-With') === 'XMLHttpRequest';
    }
}