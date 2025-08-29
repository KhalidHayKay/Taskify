<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Entity\Task;
use Slim\Views\Twig;
use App\Mail\TestMail;
use App\Mail\TaskDueMail;
use App\Mail\TaskReminderMail;
use Doctrine\ORM\EntityManager;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class MailController
{
    public function __construct(private readonly TaskReminderMail $mail, private readonly Twig $twig, private EntityManager $em) {}

    public function view(ServerRequestInterface $request, ResponseInterface $response): ResponseInterface
    {
        return $this->twig->render($response, 'mail/contact_person_request.twig', ['cp_fullname' => 'John Doe', 'fullname' => 'Jane Dane']);
    }

    public function viewTest(ServerRequestInterface $request, ResponseInterface $response): ResponseInterface
    {
        return $this->twig->render($response, 'mail/taskDue.twig', [
            'time_remaining' => '2hours, 3mins',
            'username'       => 'HayKay',
            'action_url'     => 'http//localhost:8000',
            'task'           => [
                'name'     => 'type my thang',
                'category' => 'work',
            ],
        ]);
    }

    public function test(ServerRequestInterface $request, ResponseInterface $response): ResponseInterface
    {
        $this->mail->send($request->getAttribute('user'), $this->em->find(Task::class, 91));

        return $response;
    }
}