<?php

namespace App\Tests\Security\Voter;

use App\Entity\Band;
use App\Entity\SetlistModel;
use App\Security\Voter\SetlistVoter;
use App\Service\CurrentBandResolver;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\VoterInterface;

class SetlistVoterTest extends TestCase
{
    private CurrentBandResolver $bandResolver;
    private SetlistVoter $voter;
    private TokenInterface $token;

    protected function setUp(): void
    {
        $this->bandResolver = $this->createStub(CurrentBandResolver::class);
        $this->voter = new SetlistVoter($this->bandResolver);
        $this->token = $this->createStub(TokenInterface::class);
    }

    public function testAbstainsOnUnsupportedSubject(): void
    {
        $result = $this->voter->vote($this->token, new \stdClass(), [SetlistVoter::VIEW]);

        $this->assertSame(VoterInterface::ACCESS_ABSTAIN, $result);
    }

    public function testGrantsAccessWhenSetlistBelongsToCurrentBand(): void
    {
        $band = new Band();
        $setlist = (new SetlistModel())->setBand($band);

        $this->bandResolver->method('resolve')->willReturn($band);

        $this->assertSame(
            VoterInterface::ACCESS_GRANTED,
            $this->voter->vote($this->token, $setlist, [SetlistVoter::VIEW])
        );
        $this->assertSame(
            VoterInterface::ACCESS_GRANTED,
            $this->voter->vote($this->token, $setlist, [SetlistVoter::EDIT])
        );
    }

    public function testDeniesAccessWhenSetlistBelongsToAnotherBand(): void
    {
        $setlist = (new SetlistModel())->setBand(new Band());

        $this->bandResolver->method('resolve')->willReturn(new Band());

        $this->assertSame(
            VoterInterface::ACCESS_DENIED,
            $this->voter->vote($this->token, $setlist, [SetlistVoter::VIEW])
        );
    }

    public function testDeniesAccessWhenNoCurrentBandIsResolved(): void
    {
        $setlist = (new SetlistModel())->setBand(new Band());

        $this->bandResolver->method('resolve')->willReturn(null);

        $this->assertSame(
            VoterInterface::ACCESS_DENIED,
            $this->voter->vote($this->token, $setlist, [SetlistVoter::VIEW])
        );
    }

    public function testGrantsDeleteAccessWhenSetlistBelongsToCurrentBand(): void
    {
        $band = new Band();
        $setlist = (new SetlistModel())->setBand($band);

        $this->bandResolver->method('resolve')->willReturn($band);

        $this->assertSame(
            VoterInterface::ACCESS_GRANTED,
            $this->voter->vote($this->token, $setlist, [SetlistVoter::DELETE])
        );
    }

    public function testDeniesDeleteAccessWhenSetlistBelongsToAnotherBand(): void
    {
        $setlist = (new SetlistModel())->setBand(new Band());

        $this->bandResolver->method('resolve')->willReturn(new Band());

        $this->assertSame(
            VoterInterface::ACCESS_DENIED,
            $this->voter->vote($this->token, $setlist, [SetlistVoter::DELETE])
        );
    }
}