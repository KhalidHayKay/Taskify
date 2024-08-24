<?php

declare(strict_types=1);

namespace App;

use App\Config;
use App\Entity\User;
use DateTime;
use Slim\Interfaces\RouteParserInterface;

class SignedUrl
{
    public function __construct(
        private readonly Config $config,
        private readonly RouteParserInterface $routeParser,
    ) {}

    public function formRoute(string $routeName, array $routeParams, DateTime $expiration): string
    {
        // {BASE_URL}/verify/contact_person/{USER_ID}/{HASED_EMAIL}?signature={SIGNATURE}
        // var_dump($this->config->get('app_url'), $this->config->get('app_key'));
        // exit;

        $queryParams = ['expiration' => $expiration->getTimestamp()];
        $baseUrl     = trim($this->config->get('app_url'), '/');
        $url         = $baseUrl . $this->routeParser->urlFor($routeName, $routeParams, $queryParams);

        $signature = hash_hmac('sha256', $url, $this->config->get('app_key'));

        return $baseUrl . $this->routeParser->urlFor(
            $routeName,
            $routeParams,
            $queryParams + ['signature' => $signature]
        );
    }
}