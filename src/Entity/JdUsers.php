<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use DateTime;

/**
 * @ORM\Entity(repositoryClass="App\Repository\JdUsersRepository")
 * @UniqueEntity(
 *     fields= {"email"},
 *     message="L'email vous avez indiqué est dejà utilise !"
 * )
 */
class JdUsers implements UserInterface
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(name="id", type="integer")
     */
    private $id;

    /**
     * @ORM\Column(name="name", type="string", length=30)
     * @Assert\Length(
     *      min = 4,
     *      max = 30,
     *      minMessage = "Votre nom ne peut faire moins de {{ limit }} caractères.",
     *      maxMessage = "Votre nom ne peut faire plus de {{ limit }} caractères."
     * )
     */
    private $name;

    /**
     * @ORM\Column(name="firstname", type="string", length=30)
     * @Assert\Length(
     *      min = 4,
     *      max = 30,
     *      minMessage = "Votre nom ne peut faire moins de {{ limit }} caractères.",
     *      maxMessage = "Votre nom ne peut faire plus de {{ limit }} caractères."
     * )
     */
    private $firstname;

    /**
     * @ORM\Column(name="email", type="string", length=75)
     * @Assert\Email()
     * @Assert\Length(
     *      min = 4,
     *      max = 75,
     *      minMessage = "Votre Votre adress email ne peut faire moins de {{ limit }} caractères.",
     *      maxMessage = "Votre Votre adress email ne peut faire plus de {{ limit }} caractères."
     * )
     */
    private $email;

    /**
     * @ORM\Column(type="string", length=75)
     * @Assert\Length(
     *      min = 6,
     *      minMessage = "Votre mot de passe ne peut faire moins de {{ limit }} caractères."
     * )
     */
    private $password;

    /**
     * @var
     * @Assert\EqualTo(propertyPath="password", message="Vous n'avez pas entré le même mot de passe")
     */
    private $passwordConfirm;

    /**
     * @ORM\Column(type="datetime")
     * @Assert\DateTime()
     */
    private $createdAt;

    /**
     * @ORM\Column(name="valide", type="boolean")
     * @Assert\Type(type="boolean")
     */
    private $valide = false;

    /**
     * @ORM\Column(name="roles", type="array")
     */
    private $roles =[];
     /**
     * @ORM\OneToMany(targetEntity="App\Entity\Observation", mappedBy="user", orphanRemoval=true)
     */
    private $observations;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Comment", mappedBy="author")
     */
    private $comments;

    /**
     * @ORM\Column(type="boolean")
     */
    private $alive;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\LwArticle", mappedBy="users")
     */
    private $lwArticles;

    /**
     * JdUsers constructor.
     * @param $createdAt
     */
    public function __construct()
    {
        $this->createdAt = new DateTime( 'now', new \DateTimeZone('Europe/Paris'));
        $this->roles = ['ROLE_USER'];
        $this->observations = new ArrayCollection();
        $this->comments = new ArrayCollection();
        $this->lwArticles = new ArrayCollection();
    }

    /**
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return null|string
     */
    public function getName(): ?string
    {
        return $this->name;
    }

    /**
     * @param string $name
     * @return JdUsers
     */
    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return null|string
     */
    public function getFirstname(): ?string
    {
        return $this->firstname;
    }

    /**
     * @param string $firstname
     * @return JdUsers
     */
    public function setFirstname(string $firstname): self
    {
        $this->firstname = $firstname;

        return $this;
    }

    /**
     * @return null|string
     */
    public function getEmail(): ?string
    {
        return $this->email;
    }

    /**
     * @param string $email
     * @return JdUsers
     */
    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    /**
     * @return null|string
     */
    public function getPassword(): ?string
    {

        return $this->password;
    }

    /**
     * @param string $password
     * @return JdUsers
     */
    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @return null|string
     */
    public function getPasswordConfirm(): ?string
    {
        return $this->passwordConfirm;
    }

    /**
     * @param string $passwordConfirm
     * @return JdUsers
     */
    public function setPasswordConfirm(string $passwordConfirm): self
    {
        $this->password = $passwordConfirm;

        return $this;
    }

    /**
     * @return \DateTimeInterface|null
     */
    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }

    /**
     * @param \DateTimeInterface $createdAt
     * @return JdUsers
     */
    public function setCreatedAt(\DateTimeInterface $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    /**
     * @return bool|null
     */
    public function getValide(): ?bool
    {
        return $this->valide;
    }

    /**
     * @param bool $valide
     * @return JdUsers
     */
    public function setValide(bool $valide): self
    {
        $this->valide = $valide;

        return $this;
    }

    /**
     * Returns the roles granted to the user.
     */

    public function getRoles() {
        return $this->roles;
    }

    /**
     * @param mixed $roles
     */
    public function setRoles($roles): void
    {
        $this->roles = $roles;
    }

    function addRole($role)
    {
        $this->roles[] = $role;
    }

    /**
     * Returns the salt that was originally used to encode the password.
     * This can return null if the password was not encoded using a salt.
     * @return string|null The salt
     */
    public function getSalt()
    {
        // TODO: Implement getSalt() method.
        return null;
    }

    /**
     * Returns the username used to authenticate the user.
     *
     * @return string The username
     */
    public function getUsername()
    {
        return $this->email;
    }

    /**
     * Removes sensitive data from the user.
     *
     * This is important if, at any given point, sensitive information like
     * the plain-text password is stored on this object.
     */
    public function eraseCredentials()
    {
        // TODO: Implement eraseCredentials() method.
    }

    /** @see \Serializable::serialize() */
    public function serialize() {
        return serialize(array(
            $this->id,
            $this->email,
            $this->password,
            $this->isActive,
            // see section on salt below
            // $this->salt,
        ));
    }

    /** @see \Serializable::unserialize() */
    public function unserialize($serialized)
    {
        list (
            $this->id,
            $this->email,
            $this->password,
            $this->isActive,
            // see section on salt below
            // $this->salt
            ) = unserialize($serialized);
    }
    /**
     * @return Collection|Observation[]
     */
    public function getObservations(): Collection
    {
        return $this->observations;
    }

    public function addObservation(Observation $observation): self
    {
        if (!$this->observations->contains($observation)) {
            $this->observations[] = $observation;
            $observation->setUser($this);
        }

        return $this;
    }

    public function removeObservation(Observation $observation): self
    {
        if ($this->observations->contains($observation)) {
            $this->observations->removeElement($observation);
            // set the owning side to null (unless already changed)
            if ($observation->getUser() === $this) {
                $observation->setUser(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Comment[]
     */
    public function getComments(): Collection
    {
        return $this->comments;
    }

    public function addComment(Comment $comment): self
    {
        if (!$this->comments->contains($comment)) {
            $this->comments[] = $comment;
            $comment->setAuthor($this);
        }

        return $this;
    }

    public function removeComment(Comment $comment): self
    {
        if ($this->comments->contains($comment)) {
            $this->comments->removeElement($comment);
            // set the owning side to null (unless already changed)
            if ($comment->getAuthor() === $this) {
                $comment->setAuthor(null);
            }
        }

        return $this;
    }

    public function getAlive(): ?bool
    {
        return $this->alive;
    }

    public function setAlive(bool $alive): self
    {
        $this->alive = $alive;

        return $this;
    }

    /**
     * @return Collection|LwArticle[]
     */
    public function getLwArticles(): Collection
    {
        return $this->lwArticles;
    }

    public function addLwArticle(LwArticle $lwArticle): self
    {
        if (!$this->lwArticles->contains($lwArticle)) {
            $this->lwArticles[] = $lwArticle;
            $lwArticle->setUsers($this);
        }

        return $this;
    }

    public function removeLwArticle(LwArticle $lwArticle): self
    {
        if ($this->lwArticles->contains($lwArticle)) {
            $this->lwArticles->removeElement($lwArticle);
            // set the owning side to null (unless already changed)
            if ($lwArticle->getUsers() === $this) {
                $lwArticle->setUsers(null);
            }
        }

        return $this;
    }
}
