<?php

namespace App\Entity;

use App\Repository\ReviewRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=ReviewRepository::class)
 */
class Review
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

   /**
     * @ORM\Column(type="string", length=50, unique=true)
     */
    private $username;
    /**
     * @ORM\Column(type="text")
     * @Assert\NotBlank
     * @Assert\Length(min=10)
     */

    private $content;

    /**
     * @ORM\Column(type="float")
     * @Assert\NotBlank
     */
    private $rating;

    /**
     * @ORM\Column(type="json")
     * @Assert\NotBlank
     */
    private $reactions = [];

    /**
     * @ORM\Column(type="datetime_immutable")
     * @Assert\NotBlank
     */
    private $watchedAt;

    /**
     * @ORM\ManyToOne(targetEntity=Movie::class, inversedBy="reviews")
     */
    private $movie;

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

    public function getRating(): ?int
    {
        return $this->rating;
    }

    public function setRating(int $rating): self
    {
        $this->rating = $rating;

        return $this;
    }

    public function getReactions(): ?array
    {
        return $this->reactions;
    }

    public function setReactions(array $reactions): self
    {
        $this->reactions = $reactions;

        return $this;
    }

    public function getWatchedAt(): ?\DateTimeImmutable
    {
        return $this->watchedAt;
    }

    public function setWatchedAt(?\DateTimeImmutable $watchedAt): self
    {
        $this->watchedAt = $watchedAt;

        return $this;
    }

    public function getMovie(): ?Movie
    {
        return $this->movie;
    }

    public function setMovie(?Movie $movie): self
    {
        $this->movie = $movie;

        return $this;
    }

    /**
     * Get the value of username
     */ 
    public function getUsername()
    {
        return $this->username;
    }

    /**
     * Set the value of username
     *
     * @return  self
     */ 
    public function setUsername($username)
    {
        $this->username = $username;

        return $this;
    }
}