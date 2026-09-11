<?php

namespace App\Entity;

use App\Repository\SynchronizationGameRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: SynchronizationGameRepository::class)]
class SynchronizationGame
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'synchronizationGames')]
    private ?Synchronization $synchronization = null;

    #[ORM\ManyToOne(inversedBy: 'synchronizationGames')]
    private ?Game $game = null;

    #[ORM\Column(type: Types::BIGINT)]
    private ?string $playtimeForever = null;

    #[ORM\Column(type: Types::BIGINT)]
    private ?string $playtimeTwoWeeks = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $lastPlayed = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(string $id): static
    {
        $this->id = $id;

        return $this;
    }

    public function getSynchronization(): ?Synchronization
    {
        return $this->synchronization;
    }

    public function setSynchronization(?Synchronization $synchronization): static
    {
        $this->synchronization = $synchronization;

        return $this;
    }

    public function getGame(): ?Game
    {
        return $this->game;
    }

    public function setGame(?Game $game): static
    {
        $this->game = $game;

        return $this;
    }

    public function getPlaytimeForever(): ?string
    {
        return $this->playtimeForever;
    }

    public function setPlaytimeForever(string $playtimeForever): static
    {
        $this->playtimeForever = $playtimeForever;

        return $this;
    }

    public function getPlaytimeTwoWeeks(): ?string
    {
        return $this->playtimeTwoWeeks;
    }

    public function setPlaytimeTwoWeeks(string $playtimeTwoWeeks): static
    {
        $this->playtimeTwoWeeks = $playtimeTwoWeeks;

        return $this;
    }

    public function getLastPlayed(): ?\DateTimeImmutable
    {
        return $this->lastPlayed;
    }

    public function setLastPlayed(\DateTimeImmutable $lastPlayed): static
    {
        $this->lastPlayed = $lastPlayed;

        return $this;
    }
}
