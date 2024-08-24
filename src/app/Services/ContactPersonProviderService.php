<?php

declare(strict_types=1);

namespace App\Services;

use App\DTOs\ContactPersonData;
use App\Entity\User;
use App\Entity\ContactPerson;
use Doctrine\ORM\EntityManager;

class ContactPersonProviderService
{
    public function __construct(private readonly EntityManager $entityManager) {}

    public function set(ContactPersonData $contact, User $user): ContactPerson
    {
        $isUser        = (bool) $this->entityManager->getRepository(User::class)->findOneBy(['email' => $contact->email]);
        $contactPerson = new ContactPerson();

        $contactPerson
            ->setEmail($contact->email)
            ->setFullname($contact->fullname)
            ->setIsUser($isUser)
            ->setUser($user);

        $this->entityManager->persist($contactPerson);
        $this->entityManager->flush($contactPerson);

        return $contactPerson;
    }

    public function get(User $user): ?ContactPerson
    {
        return $user->getContactPerson();
    }

    public function remove(User $user): void
    {
        $contactPerson = $user->getContactPerson();

        if ($contactPerson) {
            $this->entityManager->remove($contactPerson);
            // reset priority tasks since contact person is deleted;
            foreach ($user->getTasks() as $task) {
                $task->setIsPriority(false);
            }

            $this->entityManager->flush();
        }
    }

    public function accept(User $user)
    {
        $contactPerson = $user->getContactPerson();
        $contactPerson->setHasAccepted(true);

        $this->entityManager->flush();
    }

    public function checkAcknowledgement(User $user): bool
    {
        if (! $user->getContactPerson() || ! $user->getContactPerson()->getHasAccepted()) {
            return true;
        }

        return $user->getContactPerson()->getIsAcknowledged();
    }

    public function setAcknowledgement(User $user): void
    {
        $user->getContactPerson()->setIsAcknowledged(true);

        $this->entityManager->flush();
    }
}