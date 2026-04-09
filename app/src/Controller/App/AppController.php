<?php

namespace App\Controller\App;

use App\Entity\Band;
use App\Service\CurrentBandResolver;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

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
}
