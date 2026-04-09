<?php

namespace App\Controller\App;

use App\Entity\Band;
use App\Service\CurrentBandResolver;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;


abstract class AppController extends AbstractController
{
    private ?Band $currentBand;

    public function __construct(
        CurrentBandResolver $currentBandResolver,
    ) {
        $this->currentBand = $currentBandResolver->resolve();
    }

    protected function getCurrentBand(): ?Band
    {
        return $this->currentBand;
    }

    protected function render(string $view, array $parameters = [], ?Response $response = null): Response
    {
        $parameters['currentBand'] = $this->getCurrentBand();
        return parent::render($view, $parameters, $response);
    }
}
