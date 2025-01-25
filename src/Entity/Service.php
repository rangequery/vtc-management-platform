<?php

namespace App\Entity;

use App\Repository\ServiceRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ServiceRepository::class)]
class Service
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'services')]
    private ?Adresse $pickUpFrom = null;

    #[ORM\ManyToOne(inversedBy: 'services')]
    private ?Adresse $pickUpTo = null;

    #[ORM\Column(length: 10, nullable: true)]
    private ?string $pax = null;

    #[ORM\Column(length: 10, nullable: true)]
    private ?string $bagages = null;

    #[ORM\Column(nullable: true)]
    private ?float $montantHt = null;

    #[ORM\Column(nullable: true)]
    private ?\DateTimeImmutable $serviceAt = null;

    #[ORM\ManyToOne(inversedBy: 'services')]
    private ?Chauffeur $chauffeur = null;

    #[ORM\Column(nullable: true)]
    private ?int $driver_switch = null;

    #[ORM\Column(nullable: true)]
    private ?int $type_transfert = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $information_complementaire = null;

    #[ORM\Column(length: 100, nullable: true)]
    private ?string $message_id = null;

    #[ORM\Column(nullable: true)]
    private ?\DateTimeImmutable $finishedAt = null;

    #[ORM\Column(nullable: true)]
    private ?\DateTimeImmutable $createdAt = null;

    #[ORM\Column(nullable: true)]
    private ?\DateTimeImmutable $updatedAt = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $message_id_chauffeur = null;

    #[ORM\ManyToOne]
    private ?Type $type = null;

    #[ORM\ManyToOne(inversedBy: 'services')]
    private ?SousTraitent $sous_traitent = null;

    #[ORM\ManyToOne]
    private ?Status $status = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $notification = null;

    #[ORM\ManyToOne(inversedBy: 'services')]
    private ?Client $client = null;


    #[ORM\Column(length: 50, nullable: true)]
    private ?string $reference_number = null;

    #[ORM\ManyToOne(inversedBy: 'services')]
    private ?Demandeur $demandeur = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $infoClient = null;


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPickUpFrom(): ?Adresse
    {
        return $this->pickUpFrom;
    }

    public function setPickUpFrom(?Adresse $pickUpFrom): static
    {
        $this->pickUpFrom = $pickUpFrom;

        return $this;
    }

    public function getPickUpTo(): ?Adresse
    {
        return $this->pickUpTo;
    }

    public function setPickUpTo(?Adresse $pickUpTo): static
    {
        $this->pickUpTo = $pickUpTo;

        return $this;
    }


    public function getPax(): ?string
    {
        return $this->pax;
    }

    public function setPax(?string $pax): static
    {
        $this->pax = $pax;

        return $this;
    }

    public function getBagages(): ?string
    {
        return $this->bagages;
    }

    public function setBagages(?string $bagages): static
    {
        $this->bagages = $bagages;

        return $this;
    }

    public function getMontantHt(): ?float
    {
        return $this->montantHt;
    }

    public function setMontantHt(?float $montantHt): static
    {
        $this->montantHt = $montantHt;

        return $this;
    }

    public function getServiceAt(): ?\DateTimeImmutable
    {
        return $this->serviceAt;
    }

    public function setServiceAt(?\DateTimeImmutable $serviceAt): static
    {
        $this->serviceAt = $serviceAt;

        return $this;
    }

    public function getChauffeur(): ?Chauffeur
    {
        return $this->chauffeur;
    }

    public function setChauffeur(?Chauffeur $chauffeur): static
    {
        $this->chauffeur = $chauffeur;

        return $this;
    }


    public function getDriverSwitch(): ?int
    {
        return $this->driver_switch;
    }

    public function setDriverSwitch(?int $driver_switch): static
    {
        $this->driver_switch = $driver_switch;

        return $this;
    }

    public function getTypeTransfert(): ?int
    {
        return $this->type_transfert;
    }

    public function setTypeTransfert(?int $type_transfert): static
    {
        $this->type_transfert = $type_transfert;

        return $this;
    }

    public function getInformationComplementaire(): ?string
    {
        return $this->information_complementaire;
    }

    public function setInformationComplementaire(?string $information_complementaire): static
    {
        $this->information_complementaire = $information_complementaire;

        return $this;
    }

    public function getMessageId(): ?string
    {
        return $this->message_id;
    }

    public function setMessageId(?string $message_id): static
    {
        $this->message_id = $message_id;

        return $this;
    }

    public function getFinishedAt(): ?\DateTimeImmutable
    {
        return $this->finishedAt;
    }

    public function setFinishedAt(?\DateTimeImmutable $finishedAt): static
    {
        $this->finishedAt = $finishedAt;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(?\DateTimeImmutable $createdAt): static
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeImmutable
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(?\DateTimeImmutable $updatedAt): static
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    public function getMessageIdChauffeur(): ?string
    {
        return $this->message_id_chauffeur;
    }

    public function setMessageIdChauffeur(?string $message_id_chauffeur): static
    {
        $this->message_id_chauffeur = $message_id_chauffeur;

        return $this;
    }

    public function getType(): ?Type
    {
        return $this->type;
    }

    public function setType(?Type $type): static
    {
        $this->type = $type;

        return $this;
    }

    public function getSousTraitent(): ?SousTraitent
    {
        return $this->sous_traitent;
    }

    public function setSousTraitent(?SousTraitent $sous_traitent): static
    {
        $this->sous_traitent = $sous_traitent;

        return $this;
    }

    public function getStatus(): ?Status
    {
        return $this->status;
    }

    public function setStatus(?Status $status): static
    {
        $this->status = $status;

        return $this;
    }

    public function getNotification(): ?string
    {
        return $this->notification;
    }

    public function setNotification(?string $notification): static
    {
        $this->notification = $notification;

        return $this;
    }

    public function getClient(): ?Client
    {
        return $this->client;
    }

    public function setClient(?Client $client): static
    {
        $this->client = $client;

        return $this;
    }


    public function getReferenceNumber(): ?string
    {
        return $this->reference_number;
    }

    public function setReferenceNumber(?string $reference_number): static
    {
        $this->reference_number = $reference_number;

        return $this;
    }

    public function getDemandeur(): ?Demandeur
    {
        return $this->demandeur;
    }

    public function setDemandeur(?Demandeur $demandeur): static
    {
        $this->demandeur = $demandeur;

        return $this;
    }

    public function getInfoClient(): ?string
    {
        return $this->infoClient;
    }

    public function setInfoClient(?string $infoClient): static
    {
        $this->infoClient = $infoClient;

        return $this;
    }

}
