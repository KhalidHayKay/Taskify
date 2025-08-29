<?php

declare(strict_types=1);

namespace App\Mail;

use App\Config;
use App\Entity\User;
use App\Entity\ContactPerson;
use App\SignedUrl;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\BodyRendererInterface;

class ContactPersonRequestMail
{
    public function __construct(
        private readonly Config $config,
        private readonly BodyRendererInterface $bodyRenderer,
        private readonly MailerInterface $mailer,
        private readonly SignedUrl $signedUrl,
    ) {}

    public function send(User $user, ContactPerson $contactPerson): void
    {
        $requestUrl = $this->signedUrl->formRoute(
            'contactPersonRequest',
            ['userId' => $user->getId(), 'hash' => sha1($user->getEmail())],
            new \DateTime('+12hours')
        );

        $email = (new TemplatedEmail())
            ->from($this->config->get('mailer.from'))
            ->to($contactPerson->getEmail())
            ->subject('Request to be a Contact Person')
            ->htmlTemplate('mail/contactPersonRequest.twig')
            ->context([
                'request_url'    => $requestUrl,
                'user'           => [
                    'fullname' => $user->getFullname(),
                ],
                'contact_person' => [
                    'fullname' => $contactPerson->getFullname(),
                ],
            ]);

        $this->bodyRenderer->render($email);

        $this->mailer->send($email);
    }
}