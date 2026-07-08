<?php

namespace App\Tests\TestCase\Security\Voter;

use App\Entity\Artist;
use App\Entity\Band;
use App\Entity\Song;
use App\Security\Voter\SongVoter;
use App\Service\CurrentBandResolver;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\VoterInterface;

class SongVoterTest extends TestCase
{
    private CurrentBandResolver $bandResolver;
    private SongVoter $voter;
    private TokenInterface $token;

    protected function setUp(): void
    {
        $this->bandResolver = $this->createStub(CurrentBandResolver::class);
        $this->voter = new SongVoter($this->bandResolver);
        $this->token = $this->createStub(TokenInterface::class);
    }

    private function songForBand(Band $band): Song
    {
        $artist = (new Artist())->setBand($band);

        return (new Song())->setArtist($artist);
    }

    public function testAbstainsOnUnsupportedSubject(): void
    {
        $result = $this->voter->vote($this->token, new \stdClass(), [SongVoter::VIEW]);

        $this->assertSame(VoterInterface::ACCESS_ABSTAIN, $result);
    }

    public function testGrantsAccessWhenSongBelongsToCurrentBand(): void
    {
        $band = new Band();
        $song = $this->songForBand($band);

        $this->bandResolver->method('resolve')->willReturn($band);

        $this->assertSame(
            VoterInterface::ACCESS_GRANTED,
            $this->voter->vote($this->token, $song, [SongVoter::VIEW])
        );
        $this->assertSame(
            VoterInterface::ACCESS_GRANTED,
            $this->voter->vote($this->token, $song, [SongVoter::EDIT])
        );
    }

    public function testDeniesAccessWhenSongBelongsToAnotherBand(): void
    {
        $song = $this->songForBand(new Band());

        $this->bandResolver->method('resolve')->willReturn(new Band());

        $this->assertSame(
            VoterInterface::ACCESS_DENIED,
            $this->voter->vote($this->token, $song, [SongVoter::VIEW])
        );
    }

    public function testDeniesAccessWhenNoCurrentBandIsResolved(): void
    {
        $song = $this->songForBand(new Band());

        $this->bandResolver->method('resolve')->willReturn(null);

        $this->assertSame(
            VoterInterface::ACCESS_DENIED,
            $this->voter->vote($this->token, $song, [SongVoter::VIEW])
        );
    }

    public function testGrantsDeleteAccessWhenSongBelongsToCurrentBand(): void
    {
        $band = new Band();
        $song = $this->songForBand($band);

        $this->bandResolver->method('resolve')->willReturn($band);

        $this->assertSame(
            VoterInterface::ACCESS_GRANTED,
            $this->voter->vote($this->token, $song, [SongVoter::DELETE])
        );
    }

    public function testDeniesDeleteAccessWhenSongBelongsToAnotherBand(): void
    {
        $song = $this->songForBand(new Band());

        $this->bandResolver->method('resolve')->willReturn(new Band());

        $this->assertSame(
            VoterInterface::ACCESS_DENIED,
            $this->voter->vote($this->token, $song, [SongVoter::DELETE])
        );
    }
}
