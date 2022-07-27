<?php

namespace App\Entity;

use App\Repository\ProduitRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ProduitRepository::class)
 */
class Produit
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $nom;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $reference;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $image;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $description;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $statut;

    /**
     * @ORM\ManyToOne(targetEntity=Categorie::class, inversedBy="produits")
     * @ORM\JoinColumn(nullable=false)
     */
    private $categorie;

    /**
     * @ORM\OneToMany(targetEntity=ImgProduit::class, mappedBy="produit", orphanRemoval=true,cascade={"persist"})
     */
    private $imgproduit;

    /**
     * @ORM\Column(type="float")
     */
    private $prix;

    /**
     * @ORM\OneToMany(targetEntity=ComProduit::class, mappedBy="produit", orphanRemoval=true)
     */
    private $comproduits;
    public function __construct()
    {
        $this->imgproduit = new ArrayCollection();
        $this->comproduits = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): self
    {
        $this->nom = $nom;

        return $this;
    }

    public function getReference(): ?string
    {
        return $this->reference;
    }

    public function setReference(string $reference): self
    {
        $this->reference = $reference;

        return $this;
    }

    public function getImage(): ?string
    {
        return $this->image;
    }

    public function setImage(string $image): self
    {
        $this->image = $image;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getStatut(): ?string
    {
        return $this->statut;
    }

    public function setStatut(string $statut): self
    {
        $this->statut = $statut;

        return $this;
    }

    public function getCategorie(): ?Categorie
    {
        return $this->categorie;
    }

    public function setCategorie(?Categorie $categorie): self
    {
        $this->categorie = $categorie;

        return $this;
    }

    /**
     * @return Collection<int, ImgProduit>
     */
    public function getImgproduit(): Collection
    {
        return $this->imgproduit;
    }

    public function addImgproduit(ImgProduit $imgproduit): self
    {
        if (!$this->imgproduit->contains($imgproduit)) {
            $this->imgproduit[] = $imgproduit;
            $imgproduit->setProduit($this);
        }

        return $this;
    }

    public function removeImgproduit(ImgProduit $imgproduit): self
    {
        if ($this->imgproduit->removeElement($imgproduit)) {
            // set the owning side to null (unless already changed)
            if ($imgproduit->getProduit() === $this) {
                $imgproduit->setProduit(null);
            }
        }

        return $this;
    }

    public function getPrix(): ?float
    {
        return $this->prix;
    }

    public function setPrix(float $prix): self
    {
        $this->prix = $prix;

        return $this;
    }

    /**
     * @return Collection<int, ComProduit>
     */
    public function getComproduits(): Collection
    {
        return $this->comproduits;
    }

    public function addComproduit(ComProduit $comproduit): self
    {
        if (!$this->comproduits->contains($comproduit)) {
            $this->comproduits[] = $comproduit;
            $comproduit->setProduit($this);
        }

        return $this;
    }

    public function removeComproduit(ComProduit $comproduit): self
    {
        if ($this->comproduits->removeElement($comproduit)) {
            // set the owning side to null (unless already changed)
            if ($comproduit->getProduit() === $this) {
                $comproduit->setProduit(null);
            }
        }

        return $this;
    }

   
}
