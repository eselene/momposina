<?php

namespace App\Entity;

use App\Repository\ProduitRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: ProduitRepository::class)]
class Produit
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $nom = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $nomEs = null;

    #[ORM\Column(length: 255)]
    private ?string $description = null;

    #[ORM\Column(length: 30, nullable: true)]
    private ?string $pays = null;

    #[ORM\Column(length: 50, nullable: true)]
    private ?string $marque = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 10, scale: 2, nullable: true)]
    private ?string $poids = null;

    #[ORM\Column(length: 10, nullable: true)]
    private ?string $unitePoids = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $ingredients = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $allergenes = null;

    #[ORM\Column(length: 50, nullable: true)]
    private ?string $certification = null;

    /**
     * @Assert\Image(
     *     mimeTypes={"image/jpeg","image/jpg", "image/png"},
     *     mimeTypesMessage="Seules les images au format JPEG, JPG ou PNG sont acceptées",
     *     maxSize="122880",
     *     maxSizeMessage="L'image ne doit pas dépasser 122880b"
     * )
     */
    #[ORM\Column(length: 255, nullable: true)]
    private ?string $photo1 = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 10, scale: 2, nullable: true)]
    private ?string $prix = null;

    #[ORM\Column]
    private ?bool $visibleWeb = null;

    // #[ORM\OneToOne(cascade: ['persist', 'remove'])]
    #[ORM\OneToOne(cascade: ['persist'])]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $user = null;

    #[ORM\ManyToOne(inversedBy: 'produits')]
    #[ORM\JoinColumn(nullable: false)]
    private ?SousCategorie $sousCategorie = null;

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

    public function getNomEs(): ?string
    {
        return $this->nomEs;
    }

    public function setNomEs(?string $nomEs): static
    {
        $this->nomEs = $nomEs;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): static
    {
        $this->description = $description;

        return $this;
    }

    public function getPays(): ?string
    {
        return $this->pays;
    }

    public function setPays(?string $pays): static
    {
        $this->pays = $pays;

        return $this;
    }

    public function getMarque(): ?string
    {
        return $this->marque;
    }

    public function setMarque(?string $marque): static
    {
        $this->marque = $marque;

        return $this;
    }

    public function getPoids(): ?string
    {
        return $this->poids;
    }

    public function setPoids(?string $poids): static
    {
        $this->poids = $poids;

        return $this;
    }

    public function getUnitePoids(): ?string
    {
        return $this->unitePoids;
    }

    public function setUnitePoids(?string $unitePoids): static
    {
        $this->unitePoids = $unitePoids;

        return $this;
    }

    public function getIngredients(): ?string
    {
        return $this->ingredients;
    }

    public function setIngredients(?string $ingredients): static
    {
        $this->ingredients = $ingredients;

        return $this;
    }

    public function getAllergenes(): ?string
    {
        return $this->allergenes;
    }

    public function setAllergenes(?string $allergenes): static
    {
        $this->allergenes = $allergenes;

        return $this;
    }

    public function getCertification(): ?string
    {
        return $this->certification;
    }

    public function setCertification(?string $certification): static
    {
        $this->certification = $certification;

        return $this;
    }

    public function getPhoto1(): ?string
    {
        return $this->photo1;
    }

    public function setPhoto1(?string $photo1): static
    {
        $this->photo1 = $photo1;

        return $this;
    }

    public function getPrix(): ?string
    {
        return $this->prix;
    }

    public function setPrix(?string $prix): static
    {
        $this->prix = $prix;

        return $this;
    }
    public function getVisibleWeb(): ?bool
    {
        return $this->visibleWeb;
    }

    public function isVisibleWeb(): ?bool
    {
        return $this->visibleWeb;
    }

    public function setVisibleWeb(bool $visibleWeb): static
    {
        $this->visibleWeb = $visibleWeb;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(User $user): static
    {
        $this->user = $user;

        return $this;
    }

    public function getSousCategorie(): ?SousCategorie
    {
        return $this->sousCategorie;
    }

    public function setSousCategorie(?SousCategorie $sousCategorie): static
    {
        $this->sousCategorie = $sousCategorie;

        return $this;
    }
}
