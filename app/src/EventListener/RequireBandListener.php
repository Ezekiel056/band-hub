<?php


namespace App\EventListener;

use App\Service\CurrentBandResolver;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

class RequireBandListener implements EventSubscriberInterface
{
    public function __construct(
        private readonly CurrentBandResolver   $currentBandResolver,
        private readonly RouterInterface       $router,
        private readonly ParameterBagInterface $parameterBag,
    )
    {
    }

    public static function getSubscribedEvents(): array
    {
        return [
            KernelEvents::REQUEST => 'onKernelRequest',
        ];
    }

    public function onKernelRequest(RequestEvent $event): void
    {
        if (!$event->isMainRequest()) {
            return;
        }

        $route = $event->getRequest()->attributes->get('_route');
        $bandContext = $this->parameterBag->get('band_context');

        // On intercepte uniquement les routes app_ concernées.
        // Par défaut, toutes les routes app_ mais on peu définir des exclusions dans le fichier
        // config/band_context.yaml

        if (!str_starts_with($route ?? '', 'app_') || in_array($route, $bandContext['excluded_routes'])) {
            return; // cette route n'est pas concernée, on quitte et on laisse le traitement se poursuivre.
        }

        $band = $this->currentBandResolver->resolve();
        if (!$band) {
            $event->setResponse(
                new RedirectResponse($this->router->generate($bandContext['redirect_to']))
            );
        }
    }
}
