<?php


namespace App\Security;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\Exception\CustomUserMessageAccountStatusException;
use Symfony\Component\Security\Core\User\UserCheckerInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\User\UserInterface;

class UserChecker implements UserCheckerInterface
{
    public function __construct(
        private EntityManagerInterface $entityManager,
    ){}
    public function checkPreAuth(UserInterface $user): void
    {

        if (!$user instanceof User) { // L'utilisateur n'est pas connecté ..
            return;
        }

        if (!$user->isActive()) { // le compte n'est plus actif ..
            throw new CustomUserMessageAccountStatusException('Votre compte est désactivé.');
        }
    }

    public function checkPostAuth(UserInterface $user, ?TokenInterface $token = null): void
    {
        if (!$user instanceof User) {
            return;
        }

        $user->setLastLogin(new \DateTime());
        $this->entityManager->flush();
    }
}
