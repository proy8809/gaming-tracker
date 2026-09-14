<?php

namespace App\Entity;

use App\Repository\SynchronizationRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: SynchronizationRepository::class)]
#[ORM\HasLifecycleCallbacks]
class Synchronization
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::BIGINT)]
    private ?int $steamUserId = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $createDt = null;

    /**
     * @var Collection<int, SynchronizationGame>
     */
    #[ORM\OneToMany(targetEntity: SynchronizationGame::class, mappedBy: 'synchronization', cascade: ['persist', 'remove'])]
    private Collection $synchronizationGames;

    public function __construct()
    {
        $this->synchronizationGames = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(string $id): static
    {
        $this->id = $id;

        return $this;
    }

    public function getSteamUserId(): ?int
    {
        return $this->steamUserId;
    }

    public function setSteamUserId(?int $steamUserId): static
    {
        $this->steamUserId = $steamUserId;

        return $this;
    }

    public function getCreateDt(): ?\DateTimeImmutable
    {
        return $this->createDt;
    }

    #[ORM\PrePersist]
    public function setCreateDt(): void
    {
        $this->createDt = new \DateTimeImmutable();
    }

    /**
     * @return Collection<int, SynchronizationGame>
     */
    public function getSynchronizationGames(): Collection
    {
        return $this->synchronizationGames;
    }

    public function addSynchronizationGame(SynchronizationGame $synchronizationGame): static
    {
        if (!$this->synchronizationGames->contains($synchronizationGame)) {
            $this->synchronizationGames->add($synchronizationGame);
            $synchronizationGame->setSynchronization($this);
        }

        return $this;
    }

    public function removeSynchronizationGame(SynchronizationGame $synchronizationGame): static
    {
        if ($this->synchronizationGames->removeElement($synchronizationGame)) {
            // set the owning side to null (unless already changed)
            if ($synchronizationGame->getSynchronization() === $this) {
                $synchronizationGame->setSynchronization(null);
            }
        }

        return $this;
    }
}
