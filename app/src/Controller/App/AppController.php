<?php

namespace App\Controller\App;

use App\Entity\Band;
use App\Service\CurrentBandResolver;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response;



abstract class AppController extends AbstractController
{
    private ?Band $currentBand;

    public function __construct(
        private CurrentBandResolver $currentBandResolver,
        private RequestStack $requestStack,
    ) {
        $this->currentBand = $currentBandResolver->resolve();
    }

    protected function getCurrentBand(): ?Band
    {
        return $this->currentBand;
    }

    protected function TurboRefreshRoute(string $route, array $parameters = []) {
        return $this->render('app/_modal_success.html.twig', [
            'redirect' => $this->generateUrl($route, $parameters)
        ]);
    }


    protected function render(string $view, array $parameters = [], ?Response $response = null): Response
    {
        $parameters['currentBand'] = $this->getCurrentBand();
        $route = $this->requestStack->getCurrentRequest()->attributes->get('_route');
        $routeCollection = $this->container->get('router')->getRouteCollection();
        $parameters['selectedTab'] = $routeCollection->get($route)?->getOption('selected_tab')?->value ?? '';

        return parent::render($view, $parameters, $response);
    }
}
