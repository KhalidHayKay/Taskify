<?php

declare(strict_types=1);

namespace App\Mail;

use App\Config;
use App\Entity\Task;
use App\Entity\User;
use App\Interfaces\UserInterface;
use Doctrine\ORM\EntityManager;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Mime\Email;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\BodyRendererInterface;

class TestMail
{
    public function __construct(
        private readonly MailerInterface $mailer,
        private readonly Config $config,
        private readonly BodyRendererInterface $bodyRenderer,
        private readonly EntityManager $em,
    ) {}

    public function send(User $user, int $id): void
    {
        $task = $this->em->find(Task::class, $id);
        // var_dump($task->getDueDate()->format("Y-m-d H:i:s"));

        $email = (new TemplatedEmail())
            ->from($this->config->get('mailer.from'))
            ->to($user->getEmail())
            ->subject('Upcomming Task')
            ->htmlTemplate('mail/test.twig')
            ->context([
                'fullname' => $user->getFullname(),
                'time'     => $task->getDueDate()->format("Y-m-d H:i:s"),
            ]);

        $this->bodyRenderer->render($email);

        $this->mailer->send($email);
    }
}