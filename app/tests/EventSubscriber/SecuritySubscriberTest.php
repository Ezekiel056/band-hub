<?php

namespace App\Tests\EventSubscriber;

use App\Entity\Band;
use App\Entity\User;
use App\EventSubscriber\SecuritySubscriber;
use App\Repository\BandRepository;
use App\Service\CurrentBandResolver;
use PHPUnit\Framework\TestCase;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\HttpKernel\HttpKernelInterface;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Security\Http\Event\LoginSuccessEvent;

class SecuritySubscriberTest extends TestCase
{
    private function subscriber(
        ?BandRepository $bandRepository = null,
        ?CurrentBandResolver $bandResolver = null,
        ?Security $security = null,
        ?RouterInterface $router = null,
    ): SecuritySubscriber {
        return new SecuritySubscriber(
            $bandRepository ?? $this->createStub(BandRepository::class),
            $bandResolver ?? $this->createStub(CurrentBandResolver::class),
            $security ?? $this->createStub(Security::class),
            $router ?? $this->createStub(RouterInterface::class),
        );
    }

    public function testGetSubscribedEvents(): void
    {
        $events = SecuritySubscriber::getSubscribedEvents();

        $this->assertSame('onLoginSuccess', $events[LoginSuccessEvent::class]);
        $this->assertSame('onKernelRequest', $events[KernelEvents::REQUEST]);
    }

    private function requestEvent(bool $isMainRequest): RequestEvent
    {
        return new RequestEvent(
            $this->createStub(HttpKernelInterface::class),
            Request::create('/app/repertoire'),
            $isMainRequest ? HttpKernelInterface::MAIN_REQUEST : HttpKernelInterface::SUB_REQUEST,
        );
    }

    public function testOnKernelRequestIgnoresSubRequests(): void
    {
        $security = $this->createMock(Security::class);
        $security->expects($this->never())->method('getUser');

        $event = $this->requestEvent(isMainRequest: false);
        $this->subscriber(security: $security)->onKernelRequest($event);

        $this->assertFalse($event->hasResponse());
    }

    public function testOnKernelRequestDoesNothingForAnonymousUser(): void
    {
        $security = $this->createStub(Security::class);
        $security->method('getUser')->willReturn(null);

        $event = $this->requestEvent(isMainRequest: true);
        $this->subscriber(security: $security)->onKernelRequest($event);

        $this->assertFalse($event->hasResponse());
    }

    public function testOnKernelRequestDoesNothingForActiveUser(): void
    {
        $user = new User();
        $user->setIsActive(true);

        $security = $this->createStub(Security::class);
        $security->method('getUser')->willReturn($user);

        $event = $this->requestEvent(isMainRequest: true);
        $this->subscriber(security: $security)->onKernelRequest($event);

        $this->assertFalse($event->hasResponse());
    }

    public function testOnKernelRequestLogsOutInactiveUser(): void
    {
        $user = new User();
        $user->setIsActive(false);

        $security = $this->createStub(Security::class);
        $security->method('getUser')->willReturn($user);

        $router = $this->createMock(RouterInterface::class);
        $router->expects($this->once())
            ->method('generate')
            ->with('app_logout')
            ->willReturn('/logout');

        $event = $this->requestEvent(isMainRequest: true);
        $this->subscriber(security: $security, router: $router)->onKernelRequest($event);

        $this->assertTrue($event->hasResponse());
        $this->assertInstanceOf(RedirectResponse::class, $event->getResponse());
        $this->assertSame('/logout', $event->getResponse()->getTargetUrl());
    }

    private function loginSuccessEvent(User $user): LoginSuccessEvent
    {
        $event = $this->createStub(LoginSuccessEvent::class);
        $event->method('getUser')->willReturn($user);

        return $event;
    }

    public function testOnLoginSuccessSwitchesToLastBandWhenAvailable(): void
    {
        $lastBand = new Band();
        $user = new User();
        $user->setLastBandId(42);

        $bandRepository = $this->createMock(BandRepository::class);
        $bandRepository->expects($this->once())->method('find')->with(42)->willReturn($lastBand);
        $bandRepository->expects($this->never())->method('findFirstByUser');

        $bandResolver = $this->createMock(CurrentBandResolver::class);
        $bandResolver->expects($this->once())->method('switchTo')->with($lastBand, $user);

        $this->subscriber(bandRepository: $bandRepository, bandResolver: $bandResolver)
            ->onLoginSuccess($this->loginSuccessEvent($user));
    }

    public function testOnLoginSuccessFallsBackToFirstBandWhenNoLastBand(): void
    {
        $firstBand = new Band();
        $user = new User();

        $bandRepository = $this->createMock(BandRepository::class);
        $bandRepository->expects($this->never())->method('find');
        $bandRepository->expects($this->once())
            ->method('findFirstByUser')
            ->with($user)
            ->willReturn($firstBand);

        $bandResolver = $this->createMock(CurrentBandResolver::class);
        $bandResolver->expects($this->once())->method('switchTo')->with($firstBand, $user);

        $this->subscriber(bandRepository: $bandRepository, bandResolver: $bandResolver)
            ->onLoginSuccess($this->loginSuccessEvent($user));
    }

    public function testOnLoginSuccessDoesNothingWhenUserHasNoBand(): void
    {
        $user = new User();

        $bandRepository = $this->createStub(BandRepository::class);
        $bandRepository->method('findFirstByUser')->willReturn(null);

        $bandResolver = $this->createMock(CurrentBandResolver::class);
        $bandResolver->expects($this->never())->method('switchTo');

        $this->subscriber(bandRepository: $bandRepository, bandResolver: $bandResolver)
            ->onLoginSuccess($this->loginSuccessEvent($user));
    }
}