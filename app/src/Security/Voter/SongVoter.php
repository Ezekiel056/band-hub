<?php

namespace App\Security\Voter;

use App\Entity\Song;
use App\Entity\User;
use App\Service\CurrentBandResolver;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Vote;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\User\UserInterface;

final class SongVoter extends Voter
{
    public const EDIT = 'song.edit';
    public const VIEW = 'song.view';
    public const DELETE = 'song.delete';

    public function __construct(
        private CurrentBandResolver $currentBandResolver,
    ) {}


    protected function supports(string $attribute, mixed $subject): bool
    {
        // replace with your own logic
        // https://symfony.com/doc/current/security/voters.html
        return in_array($attribute, [self::EDIT, self::VIEW], self::DELETE)
            && $subject instanceof Song;
    }

    protected function voteOnAttribute(string $attribute, mixed $subject, TokenInterface $token, ?Vote $vote = null): bool
    {
        /** @var Song $subject */
        return $subject->getArtist()->getBand() === $this->currentBandResolver->resolve();
    }
}
