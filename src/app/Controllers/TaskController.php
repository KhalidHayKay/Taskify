<?php

declare(strict_types=1);

namespace App\Controllers;

use Slim\Views\Twig;
use App\DTOs\TaskData;
use App\Entity\User;
use App\ResponseFormatter;
use App\Serilize;
use App\Services\CategoryProviderService;
use App\Services\ContactPersonProviderService;
use App\Services\RequestService;
use Doctrine\ORM\EntityManager;
use App\Validators\TaskValidator;
use App\Validators\ValidatorFactory;
use App\Services\TaskProviderService;
use App\Validators\SetPriorityValidator;
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
    ) {}

    public function index(ServerRequestInterface $request, ResponseInterface $response): ResponseInterface
    {
        /**
         * @var User $user
         */
        $user       = $request->getAttribute('user');
        $categories = $this->categoryService->getAll($user);

        return $this->twig->render(
            $response,
            'task/index.twig',
            [
                'categories'       => $categories,
                'hasContactPerson' => ($user->getContactPerson() && $user->getContactPerson()?->getHasAccepted()),
            ]
        );
    }

    public function retrieveAll(ServerRequestInterface $request, ResponseInterface $response): ResponseInterface
    {
        $tasks = $this->taskProvider->getAll($request->getAttribute('user'));

        return $this->responseFormatter->asJson($response, $tasks)->withStatus(200);
    }

    public function retrieveForTable(ServerRequestInterface $request, ResponseInterface $response): ResponseInterface
    {
        $params = $this->requestService->getDataTableQueryParams($request);
        $tasks  = $this->taskProvider->getPaginated($params, $request->getAttribute('user')->getId());

        return $this->responseFormatter->asJson($response, [
            'data'            => array_map(fn ($task) => (new Serilize())->task($task), (array) $tasks->getIterator()),
            'draw'            => $params->draw,
            'recordsTotal'    => count($tasks),
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
            $request->getParsedBody() + ['user' => $request->getAttribute('user')]
        );

        $task = $this->taskProvider->create(new TaskData(
            $data['name'],
            $data['note'],
            $data['due_date'],
            (int) $data['category'],
            $data['user'],
            $data['priority']
        ));

        return $this->responseFormatter->asJson($response, $task)->withStatus(201);
    }

    public function remove(ServerRequestInterface $request, ResponseInterface $response, array $args): ResponseInterface
    {
        $this->taskProvider->delete((int) $args['id']);

        return $response->withHeader('Content-Type', 'application/json')->withStatus(200);
    }

    public function update(ServerRequestInterface $request, ResponseInterface $response, array $args): ResponseInterface
    {
        $data = $this->validatorFactory->resolve(TaskValidator::class)->validate(
            $args + $request->getParsedBody() + ['user' => $request->getAttribute('user')]
        );

        $this->taskProvider->edit((int) $data['id'], new TaskData(
            $data['name'],
            $data['note'],
            $data['due_date'],
            (int) $data['category'],
            $data['user'],
            $data['priority']
        ));

        return $response->withHeader('Content-Type', 'application/json')->withStatus(200);
    }

    public function setPriority(ServerRequestInterface $request, ResponseInterface $response, array $args): ResponseInterface
    {
        $data = $this->validatorFactory->resolve(SetPriorityValidator::class)->validate(
            $request->getParsedBody() + $args + ['user' => $request->getAttribute('user')]
        );

        if (! $this->taskProvider->editPriority((int) $data['id'], $data['priority'])) {
            return $response->withStatus(404);
        }

        return $response->withHeader('Content-Type', 'application/json')->withStatus(200);
    }
}