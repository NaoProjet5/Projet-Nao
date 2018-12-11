<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use EWZ\Bundle\RecaptchaBundle\Validator\Constraints as Recaptcha;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ObservationRepository")
 */
class Observation
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $nom;

    /**
     * @ORM\Column(type="datetime")
     */
    private $createdAt;
    

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\JdUsers", inversedBy="observations")
     * @ORM\JoinColumn(nullable=false)
     */
    private $user;

    /**
     * @ORM\Column(type="boolean")
     */
    private $valide;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Oiseau", inversedBy="observation")
     * @ORM\JoinColumn(nullable=false)
     */
    private $oiseau;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $longitude = null;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $latitude = null;

    /**
     * @Recaptcha\IsTrue
     */
    public $recaptcha;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Assert\File(mimeTypes={ "image/jpeg","image/png" })
     */
    private $photo;



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

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeInterface $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }



    public function getUser(): ?JdUsers
    {
        return $this->user;
    }

    public function setUser(?JdUsers $user): self
    {
        $this->user = $user;

        return $this;
    }

    public function getValide(): ?bool
    {
        return $this->valide;
    }

    public function setValide(bool $valide): self
    {
        $this->valide = $valide;

        return $this;
    }

    public function getOiseau(): ?Oiseau
    {
        return $this->oiseau;
    }

    public function setOiseau(?Oiseau $oiseau): self
    {
        $this->oiseau = $oiseau;

        return $this;
    }

    public function getLongitude(): ?string
    {
        return $this->longitude;
    }

    public function setLongitude(string $longitude): self
    {
        $this->longitude = $longitude;

        return $this;
    }

    public function getLatitude(): ?string
    {
        return $this->latitude;
    }

    public function setLatitude(string $latitude): self
    {
        $this->latitude = $latitude;

        return $this;
    }

    public function getPhoto(): ?string
    {
        return $this->photo;
    }

    public function setPhoto(?string $photo): self
    {
        $this->photo = $photo;

        return $this;
    }

    public function getImage(): ?string
    {
        return $this->image;
    }

    public function setImage(?string $image): self
    {
        $this->image = $image;

        return $this;
    }



}
