<?php


namespace App\EventSubscriber;

use App\Entity\User;
use App\Repository\BandRepository;
use App\Service\CurrentBandResolver;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Security\Http\Event\LoginSuccessEvent;

class SecuritySubscriber implements EventSubscriberInterface
{
    public function __construct(
        private BandRepository     $bandRepository,
        private CurrentBandResolver $bandResolver,
        private Security           $security,
        private RouterInterface    $router,
    ) {}


    public static function getSubscribedEvents(): array
    {
        return [
            LoginSuccessEvent::class => 'onLoginSuccess',
            KernelEvents::REQUEST => 'onKernelRequest',

        ];
    }

    private function CheckUser(RequestEvent $event) : void {
        // gestion de l'utilisateur : Sommes nous toujours actif ?
        $user = $this->security->getUser();
        if ($user instanceof User && !$user->isActive()) {
            $event->setResponse(
                new RedirectResponse($this->router->generate('app_logout'))
            );
        }
    }

    public function onKernelRequest(RequestEvent $event): void
    {
        if (!$event->isMainRequest()) {
            return;
        }

        $this->checkUser($event);
    }

    public function onLoginSuccess(LoginSuccessEvent $event): void
    {
        /** @var User $user */
        $user = $event->getUser();

        $band = null;
        $lastBandId = $user->getLastBandId();
        if ($lastBandId) {
            $band = $this->bandRepository->find($lastBandId);
        }
        if (!$band) {
            $band = $this->bandRepository->findFirstByUser($user);
        }

        if ($band) {
            $this->bandResolver->switchTo($band, $user);
        }
    }


}

