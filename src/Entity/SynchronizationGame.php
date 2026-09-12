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
    #[ORM\JoinColumn(nullable: false, onDelete: 'CASCADE')]
    private ?Synchronization $synchronization = null;

    #[ORM\ManyToOne(inversedBy: 'synchronizationGames')]
    #[ORM\JoinColumn(nullable: false, onDelete: 'CASCADE')]
    private ?Game $game = null;

    #[ORM\Column(type: Types::BIGINT)]
    private ?int $playtimeForever = null;

    #[ORM\Column(type: Types::BIGINT)]
    private ?int $playtimeTwoWeeks = null;

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

    public function getPlaytimeForever(): ?int
    {
        return $this->playtimeForever;
    }

    public function setPlaytimeForever(int $playtimeForever): static
    {
        $this->playtimeForever = $playtimeForever;

        return $this;
    }

    public function getPlaytimeTwoWeeks(): ?int
    {
        return $this->playtimeTwoWeeks;
    }

    public function setPlaytimeTwoWeeks(int $playtimeTwoWeeks): static
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
