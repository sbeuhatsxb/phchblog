<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\File;
use Vich\UploaderBundle\Mapping\Annotation as Vich;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ArticleRepository")
 * @ORM\Entity @ORM\HasLifecycleCallbacks
 * @Vich\Uploadable
 */
class Article
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
    private $title;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $content;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $createdAt;

    /**
     * @ORM\ManyToMany(targetEntity="Concept", inversedBy="articles")
     */
    private $linkedConcept;

    /**
     * @ORM\Column(type="boolean")
     */
    private $isPublished = false;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     * @var \DateTime
     */
    private $updatedAt;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Author", inversedBy="articles")
     */
    private $linkedAuthor;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="articles")
     */
    private $writer;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Category")
     */
    private $linkedCategory;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Image")
     */
    private $linkedImage;

    //not mapped property - needs to be public
    public $score;

    public function __construct()
    {
        $this->linkedConcept = new ArrayCollection();
        $this->linkedAuthor = new ArrayCollection();
        $this->linkedCategory = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(?string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getContent(): ?string
    {
        return $this->content;
    }

    public function setContent(?string $content): self
    {
        $this->content = $content;

        return $this;
    }

    public function getcreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setcreatedAt(?\DateTimeInterface $createdAt): self
    {
        $createdAt = new \DateTime('now');
        $this->createdAt = $createdAt;

        return $this;
    }

    /**
     * @return Collection|Concept[]
     */
    public function getLinkedConcept(): Collection
    {
        return $this->linkedConcept;
    }

    public function addLinkedConcept(Concept $linkedConcept): self
    {
        if (!$this->linkedConcept->contains($linkedConcept)) {
            $this->linkedConcept[] = $linkedConcept;
        }

        return $this;
    }

    public function removeLinkedConcept(Concept $linkedConcept): self
    {
        if ($this->linkedConcept->contains($linkedConcept)) {
            $this->linkedConcept->removeElement($linkedConcept);
        }

        return $this;
    }

    public function getIsPublished(): ?bool
    {
        return $this->isPublished;
    }

    public function setIsPublished(bool $isPublished): self
    {
        $this->isPublished = $isPublished;

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
     * @return Collection|Author[]
     */
    public function getLinkedAuthor(): Collection
    {
        return $this->linkedAuthor;
    }

    public function addLinkedAuthor(Author $linkedAuthor): self
    {
        if (!$this->linkedAuthor->contains($linkedAuthor)) {
            $this->linkedAuthor[] = $linkedAuthor;
        }

        return $this;
    }

    public function removeLinkedAuthor(Author $linkedAuthor): self
    {
        if ($this->linkedAuthor->contains($linkedAuthor)) {
            $this->linkedAuthor->removeElement($linkedAuthor);
        }

        return $this;
    }

    public function getWriter(): ?User
    {
        return $this->writer;
    }

    public function setWriter(?User $writer): self
    {
        $this->writer = $writer;

        return $this;
    }

    /**
     * @return Collection|Category[]
     */
    public function getLinkedCategory(): Collection
    {
        return $this->linkedCategory;
    }

    public function addLinkedCategory(Category $linkedCategory): self
    {
        if (!$this->linkedCategory->contains($linkedCategory)) {
            $this->linkedCategory[] = $linkedCategory;
        }

        return $this;
    }

    public function removeLinkedCategory(Category $linkedCategory): self
    {
        if ($this->linkedCategory->contains($linkedCategory)) {
            $this->linkedCategory->removeElement($linkedCategory);
        }

        return $this;
    }

    public function getScore(): ?int
    {
        return $this->score;
    }

    public function setScore(?int $score): self
    {
        $this->score = $score;

        return $this;
    }

    public function getLinkedImage(): ?Image
    {
        return $this->linkedImage;
    }

    public function setLinkedImage(?Image $linkedImage): self
    {
        $this->linkedImage = $linkedImage;

        return $this;
    }

    /**
     * @ORM\PrePersist
     * @ORM\PreUpdate
     */
    public function updatedTimestamps(): void
    {
        $this->setUpdatedAt(new \DateTime('now'));
        if ($this->getCreatedAt() === null) {
            $this->setCreatedAt(new \DateTime('now'));
        }
    }

    public function __toString()
    {
        return (string)$this->getTitle();
    }
}
