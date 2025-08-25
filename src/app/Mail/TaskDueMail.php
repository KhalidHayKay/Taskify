<?php

declare(strict_types=1);

namespace App\Mail;

use App\Config;
use App\SignedUrl;
use App\Entity\Task;
use App\Entity\User;
use App\Entity\ContactPerson;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\BodyRendererInterface;

class TaskDueMail
{
    public function __construct(
        private readonly Config $config,
        private readonly BodyRendererInterface $bodyRenderer,
        private readonly MailerInterface $mailer,
        private readonly SignedUrl $signedUrl,
    ) {}

    public function send(User $user, ?Task $task): void
    {
        $email = (new TemplatedEmail())
            ->from($this->config->get('mailer.from'))
            ->to($user->getEmail())
            ->subject('Task Overdue Notification')
            ->htmlTemplate('mail/taskDue.twig')
            ->context([
                'username' => $user->getUsername(),
                'task'     => [
                    'name'     => $task->getName(),
                    'category' => $task->getCategory()->getName(),
                ],
            ]);

        $this->bodyRenderer->render($email);

        $this->mailer->send($email);
    }
}