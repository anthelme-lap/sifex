<?php

namespace App\Entity;

use App\Repository\DepartRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=DepartRepository::class)
 */
class Depart
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
    private $namedepart;

    /**
     * @ORM\OneToMany(targetEntity=Demande::class, mappedBy="fkdepart")
     */
    private $demandes;

    public function __construct()
    {
        $this->demandes = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNamedepart(): ?string
    {
        return $this->namedepart;
    }

    public function setNamedepart(string $namedepart): self
    {
        $this->namedepart = $namedepart;

        return $this;
    }

    /**
     * @return Collection<int, Demande>
     */
    public function getDemandes(): Collection
    {
        return $this->demandes;
    }

    public function addDemande(Demande $demande): self
    {
        if (!$this->demandes->contains($demande)) {
            $this->demandes[] = $demande;
            $demande->setFkdepart($this);
        }

        return $this;
    }

    public function removeDemande(Demande $demande): self
    {
        if ($this->demandes->removeElement($demande)) {
            // set the owning side to null (unless already changed)
            if ($demande->getFkdepart() === $this) {
                $demande->setFkdepart(null);
            }
        }

        return $this;
    }

    public function __toString()
    {
        return $this->namedepart;
    }
}
