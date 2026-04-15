<?php

namespace App\Security;

use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\Security\Http\Authorization\AccessDeniedHandlerInterface;

class AccessDeniedHandler implements AccessDeniedHandlerInterface
{
    public function __construct(
        private RouterInterface $router,
        private RequestStack $requestStack,
    ) {}
    public function handle(Request $request, AccessDeniedException $exception): Response
    {
        // redirect ou flash + redirect
        $this->requestStack->getSession()->getFlashBag()->add('error', 'Vous n\'avez pas accès a cette ressource');
        $referer = $request->headers->get('referer');
        return new RedirectResponse($referer ?? $this->router->generate('app_repertoire'));
    }
}

