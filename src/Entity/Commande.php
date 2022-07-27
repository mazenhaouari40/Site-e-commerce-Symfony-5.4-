<?php

namespace App\Entity;

use App\Repository\CommandeRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=CommandeRepository::class)
 */
class Commande
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="commandes")
     * @ORM\JoinColumn(nullable=false)
     */
    private $User;

    /**
     * @ORM\Column(type="float")
     */
    private $total;

    /**
     * @ORM\Column(type="date", nullable=true)
     */
    private $date;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $statut;

    /**
     * @ORM\OneToMany(targetEntity=ComProduit::class, mappedBy="commande", orphanRemoval=true,cascade={"persist"})
     */
    private $comproduits;

    public function __construct()
    {
        $this->comproduits = new ArrayCollection();
    }
    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUser(): ?User
    {
        return $this->User;
    }

    public function setUser(?User $User): self
    {
        $this->User = $User;

        return $this;
    }


    public function getTotal(): ?float
    {
        return $this->total;
    }

    public function setTotal(float $total): self
    {
        $this->total = $total;

        return $this;
    }

    public function getDate(): ?\DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(?\DateTimeInterface $date): self
    {
        $this->date = $date;

        return $this;
    }

    public function getStatut(): ?string
    {
        return $this->statut;
    }

    public function setStatut(?string $statut): self
    {
        $this->statut = $statut;

        return $this;
    }

    /**
     * @return Collection<int, Comproduit>
     */
    public function getComproduits(): Collection
    {
        return $this->comproduits;
    }

    public function addComproduit(Comproduit $comproduit): self
    {
        if (!$this->comproduits->contains($comproduit)) {
            $this->comproduits[] = $comproduit;
            $comproduit->setCommande($this);
        }

        return $this;
    }

    public function removeComproduit(Comproduit $comproduit): self
    {
        if ($this->comproduits->removeElement($comproduit)) {
            // set the owning side to null (unless already changed)
            if ($comproduit->getCommande() === $this) {
                $comproduit->setCommande(null);
            }
        }
        return $this;
    }

}
