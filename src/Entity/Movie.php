<?php

namespace App\Entity;

use App\Repository\MovieRepository;
use DateTime;
use DateTimeImmutable;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass=MovieRepository::class)
 * @ORM\HasLifecycleCallbacks()
 * @UniqueEntity(fields={"title"})
 */
class Movie
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups({"movies_get_collection", "movies_get_item"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=220, unique=true)
     * @Assert\NotBlank
     * @Groups({"movies_get_collection", "movies_get_item", "castings_get_movies_collection"})
     */
    private $title;

    /**
     * @ORM\Column(type="date", nullable=true)
     * @Groups({"movies_get_item"})
     */
    private $releaseDate;

    /**
     * @ORM\Column(type="smallint", nullable=true)
     * @Assert\LessThan(1700)
     * @Assert\GreaterThan(0)
     * @Groups({"movies_get_collection", "movies_get_item"})
     */
    private $duration;

    /**
     * @ORM\OneToMany(targetEntity=Season::class, mappedBy="movie", orphanRemoval=true)
     * @Groups({"movies_get_item"})
     */
    private $seasons;

    /**
     * @ORM\ManyToMany(targetEntity=Genre::class, inversedBy="movies")
     * @Assert\Count(min=1, minMessage="Vous devez sélectionner au moins 1 genre.")
     * @Groups({"movies_get_item"})
     */
    private $genres;

    /**
     * @ORM\OneToMany(targetEntity=Casting::class, mappedBy="movie", cascade={"remove"})
     * @ORM\OrderBy({"creditOrder"="ASC"})
     * @Groups({"movies_get_item"})
     */
    private $castings;

    /**
     * @ORM\Column(type="string", length=2083, nullable=true)
     * @Assert\Url
     * @Groups({"movies_get_collection", "movies_get_item", "castings_get_movies_collection"})
     */
    private $poster;

    /**
     * @ORM\Column(type="string", length=6)
     * @Assert\NotBlank
     * @Groups({"movies_get_collection", "movies_get_item"})
     */
    private $type;

    /**
     * @ORM\Column(type="string", length=500)
     * @Assert\NotBlank
     * @Assert\Length(min=100)
     * @Groups({"movies_get_collection"})
     */
    private $summary;

    /**
     * @ORM\Column(type="text", nullable=true)
     * @Groups({"movies_get_item"})
     */
    private $synopsis;

    /**
     * @ORM\Column(type="decimal", precision=2, scale=1, nullable=true)
     * @Groups({"movies_get_collection", "movies_get_item"})
     */
    private $rating;

    /**
     * @ORM\OneToMany(targetEntity=Review::class, mappedBy="movie")
     */
    private $reviews;

    /**
     * @ORM\Column(type="string", length=320, unique=true)
     * @Groups({"movies_get_item"})
     */
    private $slug;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $updatedAt;
    
    public function __construct()
    {
        $this->releaseDate = new DateTimeImmutable();

        $this->seasons = new ArrayCollection();
        $this->genres = new ArrayCollection();
        $this->castings = new ArrayCollection();
        $this->reviews = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getReleaseDate(): ?\DateTimeInterface
    {
        return $this->releaseDate;
    }

    public function setReleaseDate(?\DateTimeInterface $releaseDate): self
    {
        $this->releaseDate = $releaseDate;

        return $this;
    }

    public function getDuration(): ?int
    {
        return $this->duration;
    }

    public function setDuration(?int $duration): self
    {
        $this->duration = $duration;

        return $this;
    }

    /**
     * @return Collection<int, Season>
     */
    public function getSeasons(): Collection
    {
        return $this->seasons;
    }

    public function addSeason(Season $season): self
    {
        if (!$this->seasons->contains($season)) {
            $this->seasons[] = $season;
            $season->setMovie($this);
        }

        return $this;
    }

    public function removeSeason(Season $season): self
    {
        if ($this->seasons->removeElement($season)) {
            if ($season->getMovie() === $this) {
                $season->setMovie(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Genre>
     */
    public function getGenres(): Collection
    {
        return $this->genres;
    }

    public function addGenre(Genre $genre): self
    {
        if (!$this->genres->contains($genre)) {
            $this->genres[] = $genre;
        }

        return $this;
    }

    public function removeGenre(Genre $genre): self
    {
        $this->genres->removeElement($genre);

        return $this;
    }

    /**
     * @return Collection<int, Casting>
     */
    public function getCastings(): Collection
    {
        return $this->castings;
    }

    public function addCasting(Casting $casting): self
    {
        if (!$this->castings->contains($casting)) {
            $this->castings[] = $casting;
            $casting->setMovie($this);
        }

        return $this;
    }

    public function removeCasting(Casting $casting): self
    {
        if ($this->castings->removeElement($casting)) {
            if ($casting->getMovie() === $this) {
                $casting->setMovie(null);
            }
        }

        return $this;
    }

    public function getPoster(): ?string
    {
        return $this->poster;
    }

    public function setPoster(?string $poster): self
    {
        $this->poster = $poster;

        return $this;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(string $type): self
    {
        $this->type = $type;

        return $this;
    }

    public function getSummary(): ?string
    {
        return $this->summary;
    }

    public function setSummary(string $summary): self
    {
        $this->summary = $summary;

        return $this;
    }

    public function getSynopsis(): ?string
    {
        return $this->synopsis;
    }

    public function setSynopsis(?string $synopsis): self
    {
        $this->synopsis = $synopsis;

        return $this;
    }

    public function getRating(): ?string
    {
        return $this->rating;
    }

    public function setRating(?string $rating): self
    {
        $this->rating = $rating;

        return $this;
    }

    /**
     * @return Collection<int, Review>
     */
    public function getReviews(): Collection
    {
        return $this->reviews;
    }

    public function addReview(Review $review): self
    {
        if (!$this->reviews->contains($review)) {
            $this->reviews[] = $review;
            $review->setMovie($this);
        }

        return $this;
    }

    public function removeReview(Review $review): self
    {
        if ($this->reviews->removeElement($review)) {
            if ($review->getMovie() === $this) {
                $review->setMovie(null);
            }
        }

        return $this;
    }

    public function getSlug(): ?string
    {
        return $this->slug;
    }

    public function setSlug(string $slug): self
    {
        $this->slug = $slug;

        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeInterface
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(?\DateTimeInterface $updatedAt): self
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    /**
     * @ORM\PreUpdate
     */
    public function setUpdatedAtValue()
    {
        // Date courante
        $this->updatedAt = new DateTime();
    }
}
