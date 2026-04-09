<?php

namespace App\Entity;

use App\Repository\BandRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: BandRepository::class)]
#[ORM\Table(name: 'bands')]
class Band
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 100)]
    private ?string $name = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $createdAt = null;

    #[ORM\Column]
    private ?bool $isActive = null;

    #[ORM\ManyToOne(inversedBy: 'bands')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $createdBy = null;

    /**
     * @var Collection<int, BandMember>
     */
    #[ORM\OneToMany(targetEntity: BandMember::class, mappedBy: 'band', orphanRemoval: true)]
    private Collection $bandMembers;

    /**
     * @var Collection<int, Artist>
     */
    #[ORM\OneToMany(targetEntity: Artist::class, mappedBy: 'band', orphanRemoval: true)]
    private Collection $artists;

    /**
     * @var Collection<int, SetlistModel>
     */
    #[ORM\OneToMany(targetEntity: SetlistModel::class, mappedBy: 'band', orphanRemoval: true)]
    private Collection $setlistModels;

    /**
     * @var Collection<int, Event>
     */
    #[ORM\OneToMany(targetEntity: Event::class, mappedBy: 'band', orphanRemoval: true)]
    private Collection $events;

    public function __construct()
    {
        $this->bandMembers = new ArrayCollection();
        $this->artists = new ArrayCollection();
        $this->setlistModels = new ArrayCollection();
        $this->events = new ArrayCollection();

        $this->createdAt = new \DateTimeImmutable();
        $this->is_active = true;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeImmutable $createdAt): static
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function isActive(): ?bool
    {
        return $this->isActive;
    }

    public function setIsActive(bool $isActive): static
    {
        $this->isActive = $isActive;

        return $this;
    }

    public function getCreatedBy(): ?User
    {
        return $this->createdBy;
    }

    public function setCreatedBy(?User $createdBy): static
    {
        $this->createdBy = $createdBy;

        return $this;
    }

    /**
     * @return Collection<int, BandMember>
     */
    public function getBandMembers(): Collection
    {
        return $this->bandMembers;
    }

    public function addBandMember(BandMember $bandMember): static
    {
        if (!$this->bandMembers->contains($bandMember)) {
            $this->bandMembers->add($bandMember);
            $bandMember->setBand($this);
        }

        return $this;
    }

    public function removeBandMember(BandMember $bandMember): static
    {
        if ($this->bandMembers->removeElement($bandMember)) {
            // set the owning side to null (unless already changed)
            if ($bandMember->getBand() === $this) {
                $bandMember->setBand(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Artist>
     */
    public function getArtists(): Collection
    {
        return $this->artists;
    }

    public function addArtist(Artist $artist): static
    {
        if (!$this->artists->contains($artist)) {
            $this->artists->add($artist);
            $artist->setBand($this);
        }

        return $this;
    }

    public function removeArtist(Artist $artist): static
    {
        if ($this->artists->removeElement($artist)) {
            // set the owning side to null (unless already changed)
            if ($artist->getBand() === $this) {
                $artist->setBand(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, SetlistModel>
     */
    public function getSetlistModels(): Collection
    {
        return $this->setlistModels;
    }

    public function addSetlistModel(SetlistModel $setlistModel): static
    {
        if (!$this->setlistModels->contains($setlistModel)) {
            $this->setlistModels->add($setlistModel);
            $setlistModel->setBand($this);
        }

        return $this;
    }

    public function removeSetlistModel(SetlistModel $setlistModel): static
    {
        if ($this->setlistModels->removeElement($setlistModel)) {
            // set the owning side to null (unless already changed)
            if ($setlistModel->getBand() === $this) {
                $setlistModel->setBand(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Event>
     */
    public function getEvents(): Collection
    {
        return $this->events;
    }

    public function addEvent(Event $event): static
    {
        if (!$this->events->contains($event)) {
            $this->events->add($event);
            $event->setBand($this);
        }

        return $this;
    }

    public function removeEvent(Event $event): static
    {
        if ($this->events->removeElement($event)) {
            // set the owning side to null (unless already changed)
            if ($event->getBand() === $this) {
                $event->setBand(null);
            }
        }

        return $this;
    }
}
