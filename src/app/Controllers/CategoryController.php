<?php

declare(strict_types=1);

namespace App\Controllers;

use App\ResponseFormatter;
use App\Serilize;
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
        private readonly ResponseFormatter $responseFormatter
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

        return $this->responseFormatter->asJson($response, $categories)->withStatus(200);
    }

    public function retrieve(ServerRequestInterface $request, ResponseInterface $response, array $args): ResponseInterface
    {
        $category = $this->categoryProvider->get((int) $args['id']);

        return $this->responseFormatter->asJson($response, $category)->withStatus(200);
    }

    public function new(ServerRequestInterface $request, ResponseInterface $response): ResponseInterface
    {
        $data = $this->validatorFactory->resolve(CategoryValidator::class)->validate(
            $request->getParsedBody() + ['user_id' => $request->getAttribute('user')->getId()]
        );

        $category = $this->categoryProvider->create($data['name'], $request->getAttribute('user'));

        return $this->responseFormatter->asJson($response, $category)->withStatus(201);
    }

    public function remove(ServerRequestInterface $request, ResponseInterface $response, array $args): ResponseInterface
    {
        $this->categoryProvider->delete((int) $args['id']);

        return $response->withHeader('Content-Type', 'application/json')->withStatus(200);
    }

    public function update(ServerRequestInterface $request, ResponseInterface $response, array $args): ResponseInterface
    {
        $data = $this->validatorFactory->resolve(CategoryValidator::class)->validate(
            $args + $request->getParsedBody() + ['user_id' => $request->getAttribute('user')->getId(), 'isEdit' => true]
        );

        $this->categoryProvider->edit((int) $data['id'], $data['name']);

        return $response->withHeader('Content-Type', 'application/json')->withStatus(200);
    }
}