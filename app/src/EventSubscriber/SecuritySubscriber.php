<?php


namespace App\EventSubscriber;

use App\Repository\BandRepository;
use App\Repository\UserRepository;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Http\Event\LoginSuccessEvent;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Bundle\SecurityBundle\Security;



class SecuritySubscriber implements EventSubscriberInterface
{
    public function __construct(
        private BandRepository         $bandRepository,
        private RequestStack           $requestStack,
        private UserRepository         $userRepository,
        private EntityManagerInterface $entityManager,
        private Security               $security,
        private TokenStorageInterface  $tokenStorage,
        private RouterInterface        $router
    )
    {
    }

    public static function getSubscribedEvents(): array
    {
        return [
            LoginSuccessEvent::class => 'onLoginSuccess',
            KernelEvents::REQUEST => 'onKernelRequest',

        ];
    }
    public function onKernelRequest(RequestEvent $event): void
    {
        if (!$event->isMainRequest()) {
        return;
        }

        // gestion de l'utilisateur : Sommes nous toujours actif ?
        $user = $this->security->getUser();
        if ($user instanceof User && !$user->isActive()) {
            // Déconnecter l'user
            $this->tokenStorage->setToken(null);
            $event->getRequest()->getSession()->invalidate();

            $event->setResponse(
                new RedirectResponse($this->router->generate('app_login'))
            );
        }
    }
    public function onLoginSuccess(LoginSuccessEvent $event): void
    {
        /** @var User $user */
        $user = $event->getUser();

        $bandId = $user->getLastBandId();
        if (!$bandId) {
            $band = $this->bandRepository->findFirstByUser($user);
            if ($band) {
                $bandId = $band->getId();
            }
        }


        //$bandID est null si le user n'a pas de groupe
        if ($bandId) {
            $this->requestStack->getSession()->set('current_band_id', $bandId);
            $user->setLastBandId($bandId);
            $this->entityManager->flush();
        }

    }
}
