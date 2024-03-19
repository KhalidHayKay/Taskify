<?php

declare(strict_types=1);

namespace App\Middlewares;

use Psr\Container\ContainerInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Slim\Views\Twig;

class CsrfFieldsMiddleware implements MiddlewareInterface
{
    public function __construct(private readonly ContainerInterface $container, private readonly Twig $twig)
    {
    }

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $csrf = $this->container->get('csrf');
        
        $csrfNameKey = $csrf->getTokenNameKey();
        $csrfValueKey = $csrf->getTokenValueKey();
        $csrfName = $csrf->getTokenName();
        $csrfValue = $csrf->getTokenValue();

        $csrfForm = <<<CSRF_FORM
        <input type="hidden" name=$csrfNameKey value=$csrfName>
        <input type="hidden" name=$csrfValueKey value=$csrfValue>
        CSRF_FORM;
       
        /* $this->twig->getEnvironment()->addGlobal('csrf', [
            'keys' => [
                'name'  => $csrfNameKey,
                'value' => $csrfValueKey
            ],
            'name'  => $csrfName,
            'value' => $csrfValue
        ]); */
        
        $this->twig->getEnvironment()->addGlobal('csrf_form', $csrfForm);

        return $handler->handle($request);
    }
}