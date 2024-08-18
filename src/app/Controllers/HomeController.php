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

        $nextTasks = $this->taskProvider->getForDashboard($user->getId(), TaskStatusEnum::Scheduled, max: 11);

        $stat = [
            'total' => count($user->getTasks()),
            'completed' => count((array) $this->taskProvider->getByStatus($user, TaskStatusEnum::Completed)),
            'overdue' => count((array) $this->taskProvider->getByStatus($user, TaskStatusEnum::OverDue)),
            'consistency' => '0%'
        ];

        $nextTask = array_shift($nextTasks);
        $upcommingTasks = $nextTasks;

        return $this->twig->render($response, 'dashboard.twig', [
            'stat' => $stat,
            'upcomming' => $upcommingTasks,
            'next' => $nextTask,
        ]);
    }
}