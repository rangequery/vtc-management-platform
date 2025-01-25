<?php

namespace App\Entity;

use App\Repository\VoitureRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: VoitureRepository::class)]
class Voiture
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 150)]
    private ?string $marque = null;

    #[ORM\Column(length: 150, nullable: true)]
    private ?string $model = null;

    #[ORM\Column(length: 150, nullable: true)]
    private ?string $immatriculation = null;

    /**
     * @var Collection<int, Chauffeur>
     */
    #[ORM\OneToMany(targetEntity: Chauffeur::class, mappedBy: 'voiture')]
    private Collection $chauffeurs;

    public function __construct()
    {
        $this->chauffeurs = new ArrayCollection();
    }

    public function __toString(): string
    {
        return $this->marque." - ".$this->model." - ".$this->immatriculation;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getMarque(): ?string
    {
        return $this->marque;
    }

    public function setMarque(string $marque): static
    {
        $this->marque = $marque;

        return $this;
    }

    public function getModel(): ?string
    {
        return $this->model;
    }

    public function setModel(?string $model): static
    {
        $this->model = $model;

        return $this;
    }

    public function getImmatriculation(): ?string
    {
        return $this->immatriculation;
    }

    public function setImmatriculation(?string $immatriculation): static
    {
        $this->immatriculation = $immatriculation;

        return $this;
    }

    /**
     * @return Collection<int, Chauffeur>
     */
    public function getChauffeurs(): Collection
    {
        return $this->chauffeurs;
    }

    public function addChauffeur(Chauffeur $chauffeur): static
    {
        if (!$this->chauffeurs->contains($chauffeur)) {
            $this->chauffeurs->add($chauffeur);
            $chauffeur->setVoiture($this);
        }

        return $this;
    }

    public function removeChauffeur(Chauffeur $chauffeur): static
    {
        if ($this->chauffeurs->removeElement($chauffeur)) {
            // set the owning side to null (unless already changed)
            if ($chauffeur->getVoiture() === $this) {
                $chauffeur->setVoiture(null);
            }
        }

        return $this;
    }
}
