<?php

namespace App\EventListener;

use App\Repository\BandRepository;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Security\Http\Event\LoginSuccessEvent;

class LoginSuccessListener implements EventSubscriberInterface
{
    public function __construct(
        private BandRepository $bandRepository,
        private RequestStack $requestStack,
    ) {}

    public static function getSubscribedEvents(): array
    {
        return [
            LoginSuccessEvent::class => 'onLoginSuccess',
        ];
    }

    public function onLoginSuccess(LoginSuccessEvent $event): void
    {
        $user = $event->getUser();
        $band = $this->bandRepository->findFirstByUser($user);

        if ($band) {
            $this->requestStack->getSession()->set('current_band_id', $band->getId());
        }
    }
}
