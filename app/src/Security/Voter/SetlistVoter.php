<?php

namespace App\Security\Voter;

use App\Entity\SetlistModel;
use App\Entity\Song;
use App\Service\CurrentBandResolver;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Vote;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

final class SetlistVoter extends Voter
{
    public const EDIT = 'setlist.edit';
    public const VIEW = 'setlist.view';
    public const DELETE = 'setlist.delete';

    public function __construct(
        private readonly CurrentBandResolver $currentBandResolver, // injection de dépendance
    ) {}
    protected function supports(string $attribute, mixed $subject): bool
    {
        return in_array($attribute, [self::EDIT, self::VIEW, self::DELETE])
            && $subject instanceof SetlistModel;
    }
    protected function voteOnAttribute(string $attribute, mixed $subject, TokenInterface $token, ?Vote $vote = null): bool
    {
        /** @var SetlistModel $subject */
        return $subject->getBand() === $this->currentBandResolver->resolve();
    }
}
