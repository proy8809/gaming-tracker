<?php

namespace App\Entity;

use App\Repository\GameRepository;
use DateTimeImmutable;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: GameRepository::class)]
#[ORM\HasLifecycleCallbacks]
class Game
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column(length: 255)]
    private ?string $imageUrl = null;

    #[ORM\Column(type: Types::BIGINT, nullable: true)]
    private ?string $pricePaid = null;

    #[ORM\Column(type: Types::BIGINT)]
    private ?int $steamUserId = null;

    #[ORM\Column(type: Types::BIGINT)]
    private ?int $steamGameId = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $createDt = null;

    /**
     * @var Collection<int, SynchronizationGame>
     */
    #[ORM\OneToMany(targetEntity: SynchronizationGame::class, mappedBy: 'game', cascade: ['persist', 'remove'])]
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

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function getImageUrl(): ?string
    {
        return $this->imageUrl;
    }

    public function setImageUrl(string $imageUrl): static
    {
        $this->imageUrl = $imageUrl;

        return $this;
    }

    public function getPricePaid(): ?string
    {
        return $this->pricePaid;
    }

    public function setPricePaid(?string $pricePaid): static
    {
        $this->pricePaid = $pricePaid;

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

    public function getSteamGameId(): ?int
    {
        return $this->steamGameId;
    }

    public function setSteamGameId(int $steamGameId): static
    {
        $this->steamGameId = $steamGameId;

        return $this;
    }

    public function getCreateDt(): ?DateTimeImmutable
    {
        return $this->createDt;
    }

    #[ORM\PrePersist]
    public function setCreateDt(): void
    {
        $this->createDt = new DateTimeImmutable();
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
            $synchronizationGame->setGame($this);
        }

        return $this;
    }

    public function removeSynchronizationGame(SynchronizationGame $synchronizationGame): static
    {
        if ($this->synchronizationGames->removeElement($synchronizationGame)) {
            // set the owning side to null (unless already changed)
            if ($synchronizationGame->getGame() === $this) {
                $synchronizationGame->setGame(null);
            }
        }

        return $this;
    }
}
