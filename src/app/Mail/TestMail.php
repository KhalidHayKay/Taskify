<?php

declare(strict_types=1);

namespace App\Mail;

use App\Config;
use App\Entity\User;
use App\Interfaces\UserInterface;
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
    )
    {
    }

    public function send(User $user): void
    {
        $email = (new TemplatedEmail())
            ->from($this->config->get('mailer.from'))
            ->to($user->getEmail())
            ->subject('New Upcomming Task')
            ->htmlTemplate('emails/taskAlert.html.twig')
            ->context([
                'name' => 'Name',
                'time' => 'Time',
            ]);

        $this->bodyRenderer->render($email);

        $this->mailer->send($email);
    }
}