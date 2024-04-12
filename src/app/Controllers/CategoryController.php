<?php

declare(strict_types=1);

namespace App\Controllers;

use Slim\Views\Twig;
use App\Services\CategoryProviderService;
use Doctrine\ORM\EntityManager;
use App\Validators\ValidatorFactory;
use App\Validators\CategoryValidator;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class CategoryController
{
    public function __construct(
        private readonly Twig $twig, 
        private readonly EntityManager $entityManager, 
        private readonly ValidatorFactory $validatorFactory,
        private readonly CategoryProviderService $categoryProvider,
    )
    {
    }

    public function index(ServerRequestInterface $request, ResponseInterface $response): ResponseInterface
    {
        return $this->twig->render($response, 'category.twig');
    }

    public function retrieveAll(ServerRequestInterface $request, ResponseInterface $response): ResponseInterface
    {
        $categories = $this->categoryProvider->getAll($request->getAttribute('user'));

        $response->getBody()->write(json_encode($categories));

        return $response->withStatus(200)->withHeader('Content-Type', 'application/json');
    }

    public function new(ServerRequestInterface $request, ResponseInterface $response): ResponseInterface
    {
        var_dump($request->getParsedBody());
        $data = $this->validatorFactory->resolve(CategoryValidator::class)->validate($request->getParsedBody());

        $this->categoryProvider->create($data['name'], $request->getAttribute('user'));

        return $response->withHeader('Content-Type', 'Application/Json')->withStatus(200);
    }

    public function remove(ServerRequestInterface $request, ResponseInterface $response): ResponseInterface
    {
        $data = $this->validatorFactory->resolve(CategoryValidator::class)->validate($request->getParsedBody());

        $this->categoryProvider->delete($data['id']);

        return $response->withHeader('Content-Type', 'Application/Json')->withStatus(200);
    }
}