<?php

namespace App\Tests\TestCase\Security;

use App\Entity\User;
use App\Security\UserChecker;
use Doctrine\ORM\EntityManagerInterface;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Security\Core\Exception\CustomUserMessageAccountStatusException;
use Symfony\Component\Security\Core\User\UserInterface;

class UserCheckerTest extends TestCase
{
    private UserChecker $checker;

    protected function setUp(): void
    {
        $this->checker = new UserChecker($this->createStub(EntityManagerInterface::class));
    }

    public function testCheckPreAuthIgnoresNonAppUser(): void
    {
        $user = $this->createStub(UserInterface::class);

        $this->checker->checkPreAuth($user);

        $this->addToAssertionCount(1); // no exception thrown
    }

    public function testCheckPreAuthAllowsActiveUser(): void
    {
        $user = new User();
        $user->setIsActive(true);

        $this->checker->checkPreAuth($user);

        $this->addToAssertionCount(1); // no exception thrown
    }

    public function testCheckPreAuthThrowsForInactiveUser(): void
    {
        $user = new User();
        $user->setIsActive(false);

        $this->expectException(CustomUserMessageAccountStatusException::class);
        $this->expectExceptionMessage('Votre compte est désactivé.');

        $this->checker->checkPreAuth($user);
    }

    public function testCheckPostAuthIgnoresNonAppUser(): void
    {
        $entityManager = $this->createMock(EntityManagerInterface::class);
        $entityManager->expects($this->never())->method('flush');
        $checker = new UserChecker($entityManager);

        $checker->checkPostAuth($this->createStub(UserInterface::class));
    }

    public function testCheckPostAuthUpdatesLastLoginAndFlushes(): void
    {
        $user = new User();
        $this->assertNull($user->getLastLogin());

        $entityManager = $this->createMock(EntityManagerInterface::class);
        $entityManager->expects($this->once())->method('flush');
        $checker = new UserChecker($entityManager);

        $checker->checkPostAuth($user);

        $this->assertInstanceOf(\DateTime::class, $user->getLastLogin());
    }
}
