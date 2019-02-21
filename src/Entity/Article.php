<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ArticleRepository")
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
    private $creationDate;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Category", inversedBy="articles")
     */
    private $category;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $author;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Concepts", inversedBy="articles")
     */
    private $linkedConcept;

    public function __construct()
    {
        $this->category = new ArrayCollection();
        $this->linkedConcept = new ArrayCollection();
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

    public function getCreationDate(): ?\DateTimeInterface
    {
        return $this->creationDate;
    }

    public function setCreationDate(?\DateTimeInterface $creationDate): self
    {
        $this->creationDate = $creationDate;

        return $this;
    }

    /**
     * @return Collection|Category[]
     */
    public function getCategory(): Collection
    {
        return $this->category;
    }

    public function addCategory(Category $category): self
    {
        if (!$this->category->contains($category)) {
            $this->category[] = $category;
        }

        return $this;
    }

    public function removeCategory(Category $category): self
    {
        if ($this->category->contains($category)) {
            $this->category->removeElement($category);
        }

        return $this;
    }

    public function getAuthor(): ?string
    {
        return $this->author;
    }

    public function setAuthor(?string $author): self
    {
        $this->author = $author;

        return $this;
    }

    /**
     * @return Collection|Concepts[]
     */
    public function getLinkedConcept(): Collection
    {
        return $this->linkedConcept;
    }

    public function addLinkedConcept(Concepts $linkedConcept): self
    {
        if (!$this->linkedConcept->contains($linkedConcept)) {
            $this->linkedConcept[] = $linkedConcept;
        }

        return $this;
    }

    public function removeLinkedConcept(Concepts $linkedConcept): self
    {
        if ($this->linkedConcept->contains($linkedConcept)) {
            $this->linkedConcept->removeElement($linkedConcept);
        }

        return $this;
    }
}
