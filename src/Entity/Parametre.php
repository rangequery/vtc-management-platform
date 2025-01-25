<?php

namespace App\Entity;

use App\Repository\ParametreRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ParametreRepository::class)]
class Parametre
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 100, nullable: true)]
    private ?string $managerId = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $prodUrl = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $devUrl = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $botToken = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getManagerId(): ?string
    {
        return $this->managerId;
    }

    public function setManagerId(?string $managerId): static
    {
        $this->managerId = $managerId;

        return $this;
    }

    public function getProdUrl(): ?string
    {
        return $this->prodUrl;
    }

    public function setProdUrl(?string $prodUrl): static
    {
        $this->prodUrl = $prodUrl;

        return $this;
    }

    public function getDevUrl(): ?string
    {
        return $this->devUrl;
    }

    public function setDevUrl(?string $devUrl): static
    {
        $this->devUrl = $devUrl;

        return $this;
    }

    public function getBotToken(): ?string
    {
        return $this->botToken;
    }

    public function setBotToken(?string $botToken): static
    {
        $this->botToken = $botToken;

        return $this;
    }
}
