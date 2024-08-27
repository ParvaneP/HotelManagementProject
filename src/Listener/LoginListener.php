<?php

namespace App\Listener;

use Symfony\Component\Security\Http\Event\LoginSuccessEvent;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\User;

class LoginListener
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function onLoginSuccess(LoginSuccessEvent $event)
    {
        $user = $event->getUser();

        // Ensure the logged-in user is an instance of your User entity
        if (!$user instanceof User) {
            return;
        }

        // Increment the login count
        $user->setLoginCount($user->getLoginCount() + 1);

        // Persist the updated user entity
        $this->entityManager->persist($user);
        $this->entityManager->flush();
    }
}
