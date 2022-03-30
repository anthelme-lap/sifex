<?php

namespace App\Entity;

use App\Repository\ArriveRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ArriveRepository::class)
 */
class Arrive
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
    private $namearrive;

    /**
     * @ORM\OneToMany(targetEntity=Demande::class, mappedBy="fkarrive")
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

    public function getNamearrive(): ?string
    {
        return $this->namearrive;
    }

    public function setNamearrive(string $namearrive): self
    {
        $this->namearrive = $namearrive;

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
            $demande->setFkarrive($this);
        }

        return $this;
    }

    public function removeDemande(Demande $demande): self
    {
        if ($this->demandes->removeElement($demande)) {
            // set the owning side to null (unless already changed)
            if ($demande->getFkarrive() === $this) {
                $demande->setFkarrive(null);
            }
        }

        return $this;
    }

    public function __toString()
    {
        return $this->namearrive;
    }
}
