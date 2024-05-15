<?php

declare(strict_types=1);

namespace App\Services;

use App\DTOs\ContactPersonData;
use App\Entity\User;
use App\Entity\ContactPerson;
use Doctrine\ORM\EntityManager;

class ContactPersonProviderService
{
    public function __construct(private readonly EntityManager $entityManager)
    {
    }

    public function crete(ContactPersonData $contact): ContactPerson
    {
        $contactPerson = new ContactPerson();

        $contactPerson
            ->setEmail($contact->email)
            ->setFullname($contact->fullname);

        return $contactPerson;
    }

    public function get(User $user): ?ContactPerson
    {
        return $this->entityManager->getRepository(ContactPerson::class)->findOneBy(['user' => $user]);
    }
}