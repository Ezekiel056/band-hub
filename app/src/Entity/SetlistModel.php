<?php

namespace App\Entity;

use App\Repository\SetlistModelRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: SetlistModelRepository::class)]
#[ORM\Table(name: 'setlists_models')]
class SetlistModel
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 100)]
    private ?string $label = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $createdAt = null;

    #[ORM\ManyToOne(inversedBy: 'setlistModels')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Band $band = null;

    /**
     * @var Collection<int, SetlistModelSong>
     */
    #[ORM\OneToMany(targetEntity: SetlistModelSong::class, mappedBy: 'setlistModel', orphanRemoval: true)]
    private Collection $setlistModelSongs;

    #[ORM\Column(length: 10)]
    private ?string $color = null;

    public function __construct()
    {
        $this->setlistModelSongs = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLabel(): ?string
    {
        return $this->label;
    }

    public function setLabel(string $label): static
    {
        $this->label = $label;

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
            $setlistModelSong->setSetlistModel($this);
        }

        return $this;
    }

    public function removeSetlistModelSong(SetlistModelSong $setlistModelSong): static
    {
        if ($this->setlistModelSongs->removeElement($setlistModelSong)) {
            // set the owning side to null (unless already changed)
            if ($setlistModelSong->getSetlistModel() === $this) {
                $setlistModelSong->setSetlistModel(null);
            }
        }

        return $this;
    }

    public function getColor(): ?string
    {
        return $this->color;
    }

    public function setColor(string $color): static
    {
        $this->color = $color;

        return $this;
    }
}
