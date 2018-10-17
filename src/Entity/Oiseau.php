<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\OiseauRepository")
 */
class Oiseau
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $famille;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $cdRef;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $rang;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $lbNom;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $lbAuteur;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $nomCompletHtml;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $nomValide;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $url;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Observation", mappedBy="oiseau", orphanRemoval=true)
     */
    private $observation;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $nomComplet;

   
    public function __construct()
    {
        $this->observation = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFamille(): ?string
    {
        return $this->famille;
    }

    public function setFamille(?string $famille): self
    {
        $this->famille = $famille;

        return $this;
    }

    public function getCdRef(): ?string
    {
        return $this->cdRef;
    }

    public function setCdRef(?string $cdRef): self
    {
        $this->cdRef = $cdRef;

        return $this;
    }

    public function getRang(): ?string
    {
        return $this->rang;
    }

    public function setRang(?string $rang): self
    {
        $this->rang = $rang;

        return $this;
    }

    public function getLbNom(): ?string
    {
        return $this->lbNom;
    }

    public function setLbNom(?string $lbNom): self
    {
        $this->lbNom = $lbNom;

        return $this;
    }

    public function getLbAuteur(): ?string
    {
        return $this->lbAuteur;
    }

    public function setLbAuteur(?string $lbAuteur): self
    {
        $this->lbAuteur = $lbAuteur;

        return $this;
    }

   
    public function getNomCompletHtml(): ?string
    {
        return $this->nomCompletHtml;
    }

    public function setNomCompletHtml(?string $nomCompletHtml): self
    {
        $this->nomCompletHtml = $nomCompletHtml;

        return $this;
    }

    public function getNomValide(): ?string
    {
        return $this->nomValide;
    }

    public function setNomValide(?string $nomValide): self
    {
        $this->nomValide = $nomValide;

        return $this;
    }

    public function getUrl(): ?string
    {
        return $this->url;
    }

    public function setUrl(?string $url): self
    {
        $this->url = $url;

        return $this;
    }

    /**
     * @return Collection|Observation[]
     */
    public function getObservation(): Collection
    {
        return $this->observation;
    }

    public function addObservation(Observation $observation): self
    {
        if (!$this->observation->contains($observation)) {
            $this->observation[] = $observation;
            $observation->setOiseau($this);
        }

        return $this;
    }

    public function removeObservation(Observation $observation): self
    {
        if ($this->observation->contains($observation)) {
            $this->observation->removeElement($observation);
            // set the owning side to null (unless already changed)
            if ($observation->getOiseau() === $this) {
                $observation->setOiseau(null);
            }
        }

        return $this;
    }

    public function getNomComplet(): ?string
    {
        return $this->nomComplet;
    }

    public function setNomComplet(?string $nomComplet): self
    {
        $this->nomComplet = $nomComplet;

        return $this;
    }

    
}
