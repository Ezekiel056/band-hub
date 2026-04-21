<?php

namespace App\Entity;

use App\Repository\SetlistModelSongRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: SetlistModelSongRepository::class)]
#[ORM\Table(name: 'setlist_models_songs')]
class SetlistModelSong
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::SMALLINT)]
    private ?int $position = null;

    #[ORM\ManyToOne(inversedBy: 'setlistModelSongs')]
    #[ORM\JoinColumn(nullable: false)]
    private ?SetlistModel $setlistModel = null;

    #[ORM\ManyToOne(inversedBy: 'setlistModelSongs')]
    #[ORM\JoinColumn(nullable: false, onDelete: 'CASCADE')]
    private ?Song $song = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPosition(): ?int
    {
        return $this->position;
    }

    public function setPosition(int $position): static
    {
        $this->position = $position;

        return $this;
    }

    public function getSetlistModel(): ?SetlistModel
    {
        return $this->setlistModel;
    }

    public function setSetlistModel(?SetlistModel $setlistModel): static
    {
        $this->setlistModel = $setlistModel;

        return $this;
    }

    public function getSong(): ?Song
    {
        return $this->song;
    }

    public function setSong(?Song $song): static
    {
        $this->song = $song;

        return $this;
    }
}
