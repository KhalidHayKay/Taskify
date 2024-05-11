<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Auth;
use App\Enums\TaskStatusEnum;
use App\Services\TaskProviderService;
use Slim\Views\Twig;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class HomeController
{
    public function __construct(
        private readonly Twig $twig,
        private readonly Auth $auth,
        private readonly TaskProviderService $taskProvider
    )
    {
    }

    public function index(ServerRequestInterface $request, ResponseInterface $response): ResponseInterface
    {
        $user = $request->getAttribute('user');

        $stat = [
            'total' => count($this->taskProvider->getAll($user)),
            'completed' => count((array) $this->taskProvider->getByStatus($user->getId(), TaskStatusEnum::Completed)->getIterator()),
            'overdue' => count((array) $this->taskProvider->getByStatus($user->getId(), TaskStatusEnum::OverDue)->getIterator()),
            'consistency' => '0%'
        ];
        $nextTask = $this->taskProvider->getForDashboard($user->getId(), TaskStatusEnum::Scheduled)[0];
        $upcommingTasks = $this->taskProvider->getForDashboard($user->getId(), TaskStatusEnum::Scheduled, false);
        $priorities = $this->taskProvider->getForDashboard($user->getId(), TaskStatusEnum::Scheduled, true);

        return $this->twig->render($response, 'dashboard.twig', [
            'stat' => $stat,
            'upcommingTasks' => $upcommingTasks,
            'priorities' => $priorities,
            'nextTask' => $nextTask ?? null,
        ]);
    }
}