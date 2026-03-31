<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ORM\UniqueConstraint(name: 'UNIQ_IDENTIFIER_USERNAME', fields: ['username'])]
#[ORM\Table(name: 'users')]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 50)]
    private ?string $username = null;

    /**
     * @var list<string> The user roles
     */
    #[ORM\Column]
    private array $roles = [];

    /**
     * @var string The hashed password
     */
    #[ORM\Column]
    private ?string $password = null;

    #[ORM\Column(length: 320)]
    private ?string $email = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $createdAt = null;

    #[ORM\Column]
    private ?bool $is_active = null;

    /**
     * @var Collection<int, Band>
     */
    #[ORM\OneToMany(targetEntity: Band::class, mappedBy: 'created_by')]
    private Collection $bands;

    /**
     * @var Collection<int, BandMember>
     */
    #[ORM\OneToMany(targetEntity: BandMember::class, mappedBy: 'user', orphanRemoval: true)]
    private Collection $bandMembers;

    /**
     * @var Collection<int, SongVote>
     */
    #[ORM\OneToMany(targetEntity: SongVote::class, mappedBy: 'user', orphanRemoval: true)]
    private Collection $songVotes;

    /**
     * @var Collection<int, Transaction>
     */
    #[ORM\OneToMany(targetEntity: Transaction::class, mappedBy: 'user')]
    private Collection $transactions;

    public function __construct()
    {
        $this->bands = new ArrayCollection();
        $this->bandMembers = new ArrayCollection();
        $this->songVotes = new ArrayCollection();
        $this->transactions = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUsername(): ?string
    {
        return $this->username;
    }

    public function setUsername(string $username): static
    {
        $this->username = $username;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return (string) $this->username;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    /**
     * @param list<string> $roles
     */
    public function setRoles(array $roles): static
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): static
    {
        $this->password = $password;

        return $this;
    }

    /**
     * Ensure the session doesn't contain actual password hashes by CRC32C-hashing them, as supported since Symfony 7.3.
     */
    public function __serialize(): array
    {
        $data = (array) $this;
        $data["\0".self::class."\0password"] = hash('crc32c', $this->password);

        return $data;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): static
    {
        $this->email = $email;

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
        return $this->is_active;
    }

    public function setIsActive(bool $is_active): static
    {
        $this->is_active = $is_active;

        return $this;
    }

    /**
     * @return Collection<int, Band>
     */
    public function getBands(): Collection
    {
        return $this->bands;
    }

    public function addBand(Band $band): static
    {
        if (!$this->bands->contains($band)) {
            $this->bands->add($band);
            $band->setCreatedBy($this);
        }

        return $this;
    }

    public function removeBand(Band $band): static
    {
        if ($this->bands->removeElement($band)) {
            // set the owning side to null (unless already changed)
            if ($band->getCreatedBy() === $this) {
                $band->setCreatedBy(null);
            }
        }

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
            $bandMember->setUser($this);
        }

        return $this;
    }

    public function removeBandMember(BandMember $bandMember): static
    {
        if ($this->bandMembers->removeElement($bandMember)) {
            // set the owning side to null (unless already changed)
            if ($bandMember->getUser() === $this) {
                $bandMember->setUser(null);
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
            $songVote->setUser($this);
        }

        return $this;
    }

    public function removeSongVote(SongVote $songVote): static
    {
        if ($this->songVotes->removeElement($songVote)) {
            // set the owning side to null (unless already changed)
            if ($songVote->getUser() === $this) {
                $songVote->setUser(null);
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
            $transaction->setUser($this);
        }

        return $this;
    }

    public function removeTransaction(Transaction $transaction): static
    {
        if ($this->transactions->removeElement($transaction)) {
            // set the owning side to null (unless already changed)
            if ($transaction->getUser() === $this) {
                $transaction->setUser(null);
            }
        }

        return $this;
    }
}
