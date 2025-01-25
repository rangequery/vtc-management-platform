<?php

namespace App\Entity;

use App\Repository\ChauffeurRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ChauffeurRepository::class)]
class Chauffeur
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 150)]
    private ?string $nom = null;

    #[ORM\Column(length: 150, nullable: true)]
    private ?string $prenom = null;

    #[ORM\ManyToOne(inversedBy: 'chauffeurs')]
    private ?Voiture $voiture = null;

    /**
     * @var Collection<int, Service>
     */
    #[ORM\OneToMany(targetEntity: Service::class, mappedBy: 'chauffeur')]
    private Collection $services;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $chatId = null;

    #[ORM\Column(length: 100, nullable: true)]
    private ?string $slug = null;

    #[ORM\Column(length: 25, nullable: true)]
    private ?string $telephone = null;

    public function __construct()
    {
        $this->services = new ArrayCollection();
    }

    public function __toString(): string
    {
        return $this->prenom." ".$this->nom;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): static
    {
        $this->nom = $nom;

        return $this;
    }

    public function getPrenom(): ?string
    {
        return $this->prenom;
    }

    public function setPrenom(?string $prenom): static
    {
        $this->prenom = $prenom;

        return $this;
    }

    public function getVoiture(): ?Voiture
    {
        return $this->voiture;
    }

    public function setVoiture(?Voiture $voiture): static
    {
        $this->voiture = $voiture;

        return $this;
    }

    /**
     * @return Collection<int, Service>
     */
    public function getServices(): Collection
    {
        return $this->services;
    }

    public function addService(Service $service): static
    {
        if (!$this->services->contains($service)) {
            $this->services->add($service);
            $service->setChauffeur($this);
        }

        return $this;
    }

    public function removeService(Service $service): static
    {
        if ($this->services->removeElement($service)) {
            // set the owning side to null (unless already changed)
            if ($service->getChauffeur() === $this) {
                $service->setChauffeur(null);
            }
        }

        return $this;
    }

    public function getChatId(): ?string
    {
        return $this->chatId;
    }

    public function setChatId(?string $chatId): static
    {
        $this->chatId = $chatId;

        return $this;
    }

    public function getSlug(): ?string
    {
        return $this->slug;
    }

    public function setSlug(?string $slug): static
    {
        $this->slug = $slug;

        return $this;
    }

    public function getTelephone(): ?string
    {
        return $this->telephone;
    }

    public function setTelephone(?string $telephone): static
    {
        $this->telephone = $telephone;

        return $this;
    }
}
