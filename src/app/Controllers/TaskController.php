<?php

declare(strict_types=1);

namespace App\Controllers;

use Slim\Views\Twig;
use App\DTOs\TaskData;
use App\ResponseFormatter;
use App\Enums\TaskStatusEnum;
use App\Serilize;
use App\Services\CategoryProviderService;
use App\Services\RequestService;
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
        private readonly RequestService $requestService,
    )
    {
    }

    public function index(ServerRequestInterface $request, ResponseInterface $response): ResponseInterface
    {
        $categories = $this->categoryService->getAll($request->getAttribute('user'));

        return $this->twig->render($response, 'task/task.twig', ['categories' => $categories]);
    }

    public function retrieveAll(ServerRequestInterface $request, ResponseInterface $response): ResponseInterface
    {
        $tasks = $this->taskProvider->getAll($request->getAttribute('user'));

        return $this->responseFormatter->asJson($response, $tasks)->withStatus(200);
    }

    public function retrieveForTable(ServerRequestInterface $request, ResponseInterface $response): ResponseInterface
    {
        $params = $this->requestService->getDataTableQueryParams($request);
        $tasks = $this->taskProvider->getPaginated($params, $request->getAttribute('user')->getId());

        return $this->responseFormatter->asJson($response, [
            'data' => array_map(fn ($task) => (new Serilize())->task($task),(array) $tasks->getIterator()),
            'draw' => $params->draw,
            'recordsTotal' => count($tasks),
            'recordsFiltered' => count($tasks),
        ])->withStatus(200);
    }

    public function retrieve(ServerRequestInterface $request, ResponseInterface $response, array $args): ResponseInterface
    {
        $task = $this->taskProvider->get((int) $args['id']);

        return $this->responseFormatter->asJson($response, $task)->withStatus(200);
    }

    public function new(ServerRequestInterface $request, ResponseInterface $response): ResponseInterface
    {
        $data = $this->validatorFactory->resolve(TaskValidator::class)->validate(
            $request->getParsedBody()
        );

        $task = $this->taskProvider->create(new TaskData(
            $data['name'],
            $data['description'],
            $data['due_date'],
            (int) $data['category'],
            $request->getAttribute('user'),
        ));

        return $this->responseFormatter->asJson($response, $task)->withStatus(201);
    }

    public function date(ServerRequestInterface $request, ResponseInterface $response, array $args): ResponseInterface
    {
        return $this->twig->render($response, 'date.twig');
    }


    public function remove(ServerRequestInterface $request, ResponseInterface $response, array $args): ResponseInterface
    {
        $this->taskProvider->delete((int) $args['id']);

        return $response->withHeader('Content-Type', 'application/json')->withStatus(200);
    }

    public function update(ServerRequestInterface $request, ResponseInterface $response, array $args): ResponseInterface
    {
        $data = $this->validatorFactory->resolve(TaskValidator::class)->validate(
            $args + $request->getParsedBody() + ['user_id' => $request->getAttribute('user')->getId(), 'isEdit' => true]
        );

        $this->taskProvider->edit((int) $data['id'], new TaskData(
            $data['name'],
            $data['description'],
            $data['due_date'],
            (int) $data['category'],
            $request->getAttribute('user'),
        ));

        return $response->withHeader('Content-Type', 'application/json')->withStatus(200);
    }
}