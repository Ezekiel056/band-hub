<?php

namespace App\Entity;

use App\Repository\EventRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: EventRepository::class)]
#[ORM\Table(name: 'events')]

class Event
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 100)]
    private ?string $title = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $description = null;

    #[ORM\Column]
    private ?\DateTime $startsAt = null;

    #[ORM\Column]
    private ?\DateTime $endsAt = null;

    #[ORM\Column]
    private ?bool $isAllDay = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    private ?EventType $eventType = null;

    #[ORM\ManyToOne(inversedBy: 'events')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Band $band = null;

    /**
     * @var Collection<int, EventSetlistSong>
     */
    #[ORM\OneToMany(targetEntity: EventSetlistSong::class, mappedBy: 'event', orphanRemoval: true)]
    private Collection $eventSetlistSongs;

    /**
     * @var Collection<int, Transaction>
     */
    #[ORM\OneToMany(targetEntity: Transaction::class, mappedBy: 'event')]
    private Collection $transactions;

    public function __construct()
    {
        $this->eventSetlistSongs = new ArrayCollection();
        $this->transactions = new ArrayCollection();
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

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): static
    {
        $this->description = $description;

        return $this;
    }

    public function getStartsAt(): ?\DateTime
    {
        return $this->startsAt;
    }

    public function setStartsAt(\DateTime $startsAt): static
    {
        $this->startsAt = $startsAt;

        return $this;
    }

    public function getEndsAt(): ?\DateTime
    {
        return $this->endsAt;
    }

    public function setEndsAt(\DateTime $endsAt): static
    {
        $this->endsAt = $endsAt;

        return $this;
    }

    public function isAllDay(): ?bool
    {
        return $this->isAllDay;
    }

    public function setIsAllDay(bool $isAllDay): static
    {
        $this->isAllDay = $isAllDay;

        return $this;
    }

    public function getEventType(): ?EventType
    {
        return $this->eventType;
    }

    public function setEventType(?EventType $eventType): static
    {
        $this->eventType = $eventType;

        return $this;
    }

    public function getBand(): ?Band
    {
        return $this->band;
    }

    public function setBand(?Band $band): static
    {
        $this->band = $band;

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
            $eventSetlistSong->setEvent($this);
        }

        return $this;
    }

    public function removeEventSetlistSong(EventSetlistSong $eventSetlistSong): static
    {
        if ($this->eventSetlistSongs->removeElement($eventSetlistSong)) {
            // set the owning side to null (unless already changed)
            if ($eventSetlistSong->getEvent() === $this) {
                $eventSetlistSong->setEvent(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Transaction>
     */
    public function getTransactions(): Collection
    {
        return $this->transactions;
    }

    public function addTransaction(Transaction $transaction): static
    {
        if (!$this->transactions->contains($transaction)) {
            $this->transactions->add($transaction);
            $transaction->setEvent($this);
        }

        return $this;
    }

    public function removeTransaction(Transaction $transaction): static
    {
        if ($this->transactions->removeElement($transaction)) {
            // set the owning side to null (unless already changed)
            if ($transaction->getEvent() === $this) {
                $transaction->setEvent(null);
            }
        }

        return $this;
    }
}
