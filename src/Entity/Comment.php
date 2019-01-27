<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use EWZ\Bundle\RecaptchaBundle\Validator\Constraints as Recaptcha;

/**
 * @ORM\Entity(repositoryClass="App\Repository\CommentRepository")
 */
class Comment
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="text")
     */
    private $content;

    /**
     * @ORM\Column(type="datetime")
     */
    private $createdAt;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\JdUsers", inversedBy="comments")
     */
    private $author;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\LwArticle", inversedBy="comments")
     */
    private $article;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $signale;
    /**
     * @Recaptcha\IsTrue
     */
    public $recaptcha;


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getContent(): ?string
    {
        return $this->content;
    }

    public function setContent(string $content): self
    {
        $this->content = $content;

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

    public function getAuthor(): ?JdUsers
    {
        return $this->author;
    }

    public function setAuthor(?JdUsers $author): self
    {
        $this->author = $author;

        return $this;
    }

    public function getArticle(): ?LwArticle
    {
        return $this->article;
    }

    public function setArticle(?LwArticle $article): self
    {
        $this->article = $article;

        return $this;
    }

    public function getSignale(): ?bool
    {
        return $this->signale;
    }

    public function setSignale(?bool $signale): self
    {
        $this->signale = $signale;

        return $this;
    }




}
