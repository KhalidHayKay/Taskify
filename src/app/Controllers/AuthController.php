<?php

declare(strict_types=1);

namespace App\Controllers;

use Slim\Views\Twig;
use App\DTOs\UserRegData;
use App\DTOs\UserLoginData;
use App\Validators\LoginValidator;
use App\Validators\SignUpValidator;
use App\Validators\ValidatorFactory;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use App\Exceptions\InvalidCredentialsException;
use App\Interfaces\AuthInterface;

class AuthController
{
    public function __construct(
        private readonly Twig $twig, 
        private readonly AuthInterface $auth,
        private readonly ValidatorFactory $validatorFactory,
    )
    {
    }

    public function loginView(ServerRequestInterface $request, ResponseInterface $response): ResponseInterface
    {
        return $this->twig->render($response, 'auth/login.twig');
    }

    public function signupView(ServerRequestInterface $request, ResponseInterface $response): ResponseInterface
    {
        return $this->twig->render($response, 'auth/signup.twig');
    }

    public function login(ServerRequestInterface $request, ResponseInterface $response): ResponseInterface
    {
        $data = $this->validatorFactory->resolve(LoginValidator::class)->validate($request->getParsedBody());

        if(! $this->auth->attemptLogin(new UserLoginData($data['email'], $data['password']))) {
            throw new InvalidCredentialsException(['password' => ['Credentials does not match. Try again!']]);
        }

        return $response->withHeader('Location', '/')->withStatus(302);
    }

    public function signup(ServerRequestInterface $request, ResponseInterface $response): ResponseInterface
    {
        $data = $this->validatorFactory->resolve(SignUpValidator::class)->validate($request->getParsedBody());

        $this->auth->register(new UserRegData(
            $data['fullname'], 
            $data['username'], 
            $data['email'], 
            $data['password']
        ));

        return $response->withHeader('Location', '/')->withStatus(302);
    }

    public function logout(ServerRequestInterface $request, ResponseInterface $response): ResponseInterface
    {
        $this->auth->logout();

        return $response->withHeader('Location', '/')->withStatus(302);
    }
}