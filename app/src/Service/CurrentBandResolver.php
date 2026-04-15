<?php

namespace App\Service;

use App\Entity\Band;
use App\Entity\User;
use App\Repository\BandRepository;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\RequestStack;

class CurrentBandResolver
{
    public function __construct(
        private RequestStack $requestStack,
        private BandRepository $bandRepository,
        private Security $security,
    ) {}

    public function resolve(): ?Band
    {
        $session = $this->requestStack->getSession();
        $bandId = $session->get('current_band_id');

        if (!$bandId) {
            return null;
        }

        return $this->bandRepository->find($bandId);
    }

    public function resolveRoles(): array
    {
        $band = $this->resolve();
        /** @var User $user */
        $user = $this->security->getUser();

        $member = $user->getBandMembers()->filter(
            fn($m) => $m->getBand() === $band
        )->first();

        return $member ? $member->getRoles() : [];
    }
}
