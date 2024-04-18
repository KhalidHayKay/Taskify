<?php

declare(strict_types=1);

namespace App\Controllers;

use Slim\Views\Twig;
use App\DTOs\TaskData;
use App\ResponseFormatter;
use App\Enums\TaskStatusEnum;
use App\Services\CategoryProviderService;
use Doctrine\ORM\EntityManager;
use App\Validators\TaskValidator;
use App\Validators\ValidatorFactory;
use App\Services\TaskProviderService;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class TaskController
{
    public function __construct(
        private readonly Twig $twig, 
        private readonly EntityManager $entityManager, 
        private readonly ValidatorFactory $validatorFactory,
        private readonly TaskProviderService $taskProvider,
        private readonly ResponseFormatter $responseFormatter,
        private readonly CategoryProviderService $categoryService,
    )
    {
    }

    public function index(ServerRequestInterface $request, ResponseInterface $response): ResponseInterface
    {
        $categories = $this->categoryService->getAll($request->getAttribute('user'));

        // var_dump($categories);

        return $this->twig->render($response, 'task/task.twig', ['categories' => $categories]);
    }

    public function retrieveAll(ServerRequestInterface $request, ResponseInterface $response): ResponseInterface
    {
        $task = $this->taskProvider->getAll($request->getAttribute('user'));

        return $this->responseFormatter->asJson($response, $task)->withStatus(200);
    }

    public function retrieve(ServerRequestInterface $request, ResponseInterface $response, array $args): ResponseInterface
    {
        $task = $this->taskProvider->get((int) $args['id']);

        return $this->responseFormatter->asJson($response, $task)->withStatus(200);
    }

    public function new(ServerRequestInterface $request, ResponseInterface $response): ResponseInterface
    {
        $data = $this->validatorFactory->resolve(TaskValidator::class)->validate(
            $request->getParsedBody() + ['user_id' => $request->getAttribute('user')->getId()]
        );

        $task = $this->taskProvider->create(new TaskData(
            $data['name'],
            $data['description'],
            $data['dueDate'],
            TaskStatusEnum::Scheduled,
            $data['id'],
            $request->getAttribute('user'),
        ));

        return $this->responseFormatter->asJson($response, $task)->withStatus(201);
    }

    // public function remove(ServerRequestInterface $request, ResponseInterface $response, array $args): ResponseInterface
    // {
    //     $this->categoryProvider->delete((int) $args['id']);

    //     return $response->withHeader('Content-Type', 'application/json')->withStatus(200);
    // }

    // public function update(ServerRequestInterface $request, ResponseInterface $response, array $args): ResponseInterface
    // {
    //     $data = $this->validatorFactory->resolve(CategoryValidator::class)->validate(
    //         $args + $request->getParsedBody() + ['user_id' => $request->getAttribute('user')->getId(), 'isEdit' => true]
    //     );

    //     $this->categoryProvider->edit((int) $data['id'], $data['name']);

    //     return $response->withHeader('Content-Type', 'application/json')->withStatus(200);
    // }
}