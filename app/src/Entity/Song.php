<?php

namespace App\Entity;

use App\Repository\SongRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: SongRepository::class)]
#[ORM\Table(name: 'songs')]
class Song
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 100)]
    private ?string $title = null;

    #[ORM\Column(type: Types::SMALLINT, nullable: true)]
    private ?int $duration = null;

    #[ORM\Column(type: Types::SMALLINT, nullable: true)]
    private ?int $bpm = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $lyrics = null;

    #[ORM\ManyToOne(inversedBy: 'songs')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Artist $artist = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    private ?SongStatus $status = null;

    /**
     * @var Collection<int, BackingTrack>
     */
    #[ORM\OneToMany(targetEntity: BackingTrack::class, mappedBy: 'song', orphanRemoval: true)]
    private Collection $backingTracks;

    /**
     * @var Collection<int, SongLink>
     */
    #[ORM\OneToMany(targetEntity: SongLink::class, mappedBy: 'song', orphanRemoval: true)]
    private Collection $songLinks;

    /**
     * @var Collection<int, SetlistModelSong>
     */
    #[ORM\OneToMany(targetEntity: SetlistModelSong::class, mappedBy: 'song', orphanRemoval: true)]
    private Collection $setlistModelSongs;

    /**
     * @var Collection<int, EventSetlistSong>
     */
    #[ORM\OneToMany(targetEntity: EventSetlistSong::class, mappedBy: 'song', orphanRemoval: true)]
    private Collection $eventSetlistSongs;

    /**
     * @var Collection<int, SongVote>
     */
    #[ORM\OneToMany(targetEntity: SongVote::class, mappedBy: 'song', orphanRemoval: true)]
    private Collection $songVotes;

    public function __construct()
    {
        $this->backingTracks = new ArrayCollection();
        $this->songLinks = new ArrayCollection();
        $this->setlistModelSongs = new ArrayCollection();
        $this->eventSetlistSongs = new ArrayCollection();
        $this->songVotes = new ArrayCollection();
    }



    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): static
    {
        $this->title = $title;

        return $this;
    }

    public function getDuration(): ?int
    {
        return $this->duration;
    }

    public function setDuration(?int $duration): static
    {
        $this->duration = $duration;

        return $this;
    }

    public function getBpm(): ?int
    {
        return $this->bpm;
    }

    public function setBpm(?int $bpm): static
    {
        $this->bpm = $bpm;

        return $this;
    }

    public function getLyrics(): ?string
    {
        return $this->lyrics;
    }

    public function setLyrics(?string $lyrics): static
    {
        $this->lyrics = $lyrics;

        return $this;
    }

    public function getArtist(): ?Artist
    {
        return $this->artist;
    }

    public function setArtist(?Artist $artist): static
    {
        $this->artist = $artist;

        return $this;
    }

    public function getStatus(): ?SongStatus
    {
        return $this->status;
    }

    public function setStatus(?SongStatus $status): static
    {
        $this->status = $status;

        return $this;
    }

    /**
     * @return Collection<int, BackingTrack>
     */
    public function getBackingTracks(): Collection
    {
        return $this->backingTracks;
    }

    public function addBackingTrack(BackingTrack $backingTrack): static
    {
        if (!$this->backingTracks->contains($backingTrack)) {
            $this->backingTracks->add($backingTrack);
            $backingTrack->setSong($this);
        }

        return $this;
    }

    public function removeBackingTrack(BackingTrack $backingTrack): static
    {
        if ($this->backingTracks->removeElement($backingTrack)) {
            // set the owning side to null (unless already changed)
            if ($backingTrack->getSong() === $this) {
                $backingTrack->setSong(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, SongLink>
     */
    public function getSongLinks(): Collection
    {
        return $this->songLinks;
    }

    public function addSongLink(SongLink $songLink): static
    {
        if (!$this->songLinks->contains($songLink)) {
            $this->songLinks->add($songLink);
            $songLink->setSong($this);
        }

        return $this;
    }

    public function removeSongLink(SongLink $songLink): static
    {
        if ($this->songLinks->removeElement($songLink)) {
            // set the owning side to null (unless already changed)
            if ($songLink->getSong() === $this) {
                $songLink->setSong(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, SetlistModelSong>
     */
    public function getSetlistModelSongs(): Collection
    {
        return $this->setlistModelSongs;
    }

    public function addSetlistModelSong(SetlistModelSong $setlistModelSong): static
    {
        if (!$this->setlistModelSongs->contains($setlistModelSong)) {
            $this->setlistModelSongs->add($setlistModelSong);
            $setlistModelSong->setSong($this);
        }

        return $this;
    }

    public function removeSetlistModelSong(SetlistModelSong $setlistModelSong): static
    {
        if ($this->setlistModelSongs->removeElement($setlistModelSong)) {
            // set the owning side to null (unless already changed)
            if ($setlistModelSong->getSong() === $this) {
                $setlistModelSong->setSong(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, EventSetlistSong>
     */
    public function getEventSetlistSongs(): Collection
    {
        return $this->eventSetlistSongs;
    }

    public function addEventSetlistSong(EventSetlistSong $eventSetlistSong): static
    {
        if (!$this->eventSetlistSongs->contains($eventSetlistSong)) {
            $this->eventSetlistSongs->add($eventSetlistSong);
            $eventSetlistSong->setSong($this);
        }

        return $this;
    }

    public function removeEventSetlistSong(EventSetlistSong $eventSetlistSong): static
    {
        if ($this->eventSetlistSongs->removeElement($eventSetlistSong)) {
            // set the owning side to null (unless already changed)
            if ($eventSetlistSong->getSong() === $this) {
                $eventSetlistSong->setSong(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, SongVote>
     */
    public function getSongVotes(): Collection
    {
        return $this->songVotes;
    }

    public function addSongVote(SongVote $songVote): static
    {
        if (!$this->songVotes->contains($songVote)) {
            $this->songVotes->add($songVote);
            $songVote->setSong($this);
        }

        return $this;
    }

    public function removeSongVote(SongVote $songVote): static
    {
        if ($this->songVotes->removeElement($songVote)) {
            // set the owning side to null (unless already changed)
            if ($songVote->getSong() === $this) {
                $songVote->setSong(null);
            }
        }

        return $this;
    }


}
