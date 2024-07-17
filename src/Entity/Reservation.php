<?php

namespace App\Entity;

use App\Repository\ReservationRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use phpDocumentor\Reflection\Types\Nullable;

#[ORM\Entity(repositoryClass: ReservationRepository::class)]
class Reservation
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 80)]
    private ?string $nomParticipant = null;

    #[ORM\Column(length: 80)]
    private ?string $prenomParticipant = null;

    #[ORM\Column(length: 180)]
    private ?string $emailParticipant = null;

    #[ORM\Column(length: 20, nullable: true)]
    private ?string $telephoneParticipant = null;

    #[ORM\Column(type: Types::SMALLINT)]
    private ?int $nombrePlaces = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $dateReservation = null;

    #[ORM\Column(length: 50, nullable: true)]
    private ?string $modePaiement = null;

    #[ORM\Column]
    private ?bool $mailReservaEnvoye = null;

    #[ORM\Column(length: 50)]
    private ?string $statusReservation = null;

    #[ORM\OneToOne(inversedBy: 'reservation', cascade: ['persist', 'remove'])]
    #[ORM\JoinColumn(nullable: true)]
    private ?User $user = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNomParticipant(): ?string
    {
        return $this->nomParticipant;
    }

    public function setNomParticipant(string $nomParticipant): static
    {
        $this->nomParticipant = $nomParticipant;

        return $this;
    }

    public function getPrenomParticipant(): ?string
    {
        return $this->prenomParticipant;
    }

    public function setPrenomParticipant(string $prenomParticipant): static
    {
        $this->prenomParticipant = $prenomParticipant;

        return $this;
    }

    public function getEmailParticipant(): ?string
    {
        return $this->emailParticipant;
    }

    public function setEmailParticipant(string $emailParticipant): static
    {
        $this->emailParticipant = $emailParticipant;

        return $this;
    }

    public function getTelephoneParticipant(): ?string
    {
        return $this->telephoneParticipant;
    }

    public function setTelephoneParticipant(?string $telephoneParticipant): static
    {
        $this->telephoneParticipant = $telephoneParticipant;

        return $this;
    }

    public function getNombrePlaces(): ?int
    {
        return $this->nombrePlaces;
    }

    public function setNombrePlaces(int $nombrePlaces): static
    {
        $this->nombrePlaces = $nombrePlaces;

        return $this;
    }

    public function getDateReservation(): ?\DateTimeInterface
    {
        return $this->dateReservation;
    }

    public function setDateReservation(\DateTimeInterface $dateReservation): static
    {
        $this->dateReservation = $dateReservation;

        return $this;
    }

    public function getModePaiement(): ?string
    {
        return $this->modePaiement;
    }

    public function setModePaiement(?string $modePaiement): static
    {
        $this->modePaiement = $modePaiement;

        return $this;
    }

    public function isMailReservaEnvoye(): ?bool
    {
        return $this->mailReservaEnvoye;
    }

    public function setMailReservaEnvoye(bool $mailReservaEnvoye): static
    {
        $this->mailReservaEnvoye = $mailReservaEnvoye;

        return $this;
    }

    public function getStatusReservation(): ?string
    {
        return $this->statusReservation;
    }

    public function setStatusReservation(string $statusReservation): static
    {
        $this->statusReservation = $statusReservation;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): static
    {
        $this->user = $user;

        return $this;
    }
}
