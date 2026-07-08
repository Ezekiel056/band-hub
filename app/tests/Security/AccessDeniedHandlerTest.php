<?php

namespace App\Tests\Security;

use App\Security\AccessDeniedHandler;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Session\FlashBagAwareSessionInterface;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

class AccessDeniedHandlerTest extends TestCase
{
    private function handlerWithFlashBag(FlashBagInterface $flashBag, RouterInterface $router): AccessDeniedHandler
    {
        $session = $this->createStub(FlashBagAwareSessionInterface::class);
        $session->method('getFlashBag')->willReturn($flashBag);

        $requestStack = $this->createStub(RequestStack::class);
        $requestStack->method('getSession')->willReturn($session);

        return new AccessDeniedHandler($router, $requestStack);
    }

    public function testHandleAddsErrorFlashMessage(): void
    {
        $flashBag = $this->createMock(FlashBagInterface::class);
        $flashBag->expects($this->once())
            ->method('add')
            ->with('error', 'Vous n\'avez pas accès a cette ressource');

        $router = $this->createStub(RouterInterface::class);
        $router->method('generate')->willReturn('/app/repertoire');

        $handler = $this->handlerWithFlashBag($flashBag, $router);

        $handler->handle(Request::create('/app/repertoire'), new AccessDeniedException());
    }

    public function testHandleRedirectsToRefererWhenPresent(): void
    {
        $router = $this->createMock(RouterInterface::class);
        $router->expects($this->never())->method('generate');

        $handler = $this->handlerWithFlashBag($this->createStub(FlashBagInterface::class), $router);

        $request = Request::create('/app/repertoire');
        $request->headers->set('referer', 'https://band-hub.test/app/setlists');

        $response = $handler->handle($request, new AccessDeniedException());

        $this->assertInstanceOf(RedirectResponse::class, $response);
        $this->assertSame('https://band-hub.test/app/setlists', $response->getTargetUrl());
    }

    public function testHandleRedirectsToRepertoireWhenNoReferer(): void
    {
        $router = $this->createMock(RouterInterface::class);
        $router->expects($this->once())
            ->method('generate')
            ->with('app_repertoire')
            ->willReturn('/app/repertoire');

        $handler = $this->handlerWithFlashBag($this->createStub(FlashBagInterface::class), $router);

        $response = $handler->handle(Request::create('/app/repertoire'), new AccessDeniedException());

        $this->assertInstanceOf(RedirectResponse::class, $response);
        $this->assertSame('/app/repertoire', $response->getTargetUrl());
    }
}