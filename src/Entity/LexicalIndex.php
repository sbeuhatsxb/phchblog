<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\LexicalIndexRepository")
 * @ORM\Table(indexes={
 *   @ORM\Index(name="word_idx", columns={"word"}),
 *   @ORM\Index(name="metaphone_idx", columns={"metaphone"})
 * })
 */
class LexicalIndex
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=30,  unique=true)
     */
    private $word;

    /**
     * @ORM\Column(type="string", length=30, nullable=true)
     */
    private $metaphone;


    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Article", mappedBy="lexicalIndex")
     */
    private $linkedArticles;

    public function __construct()
    {
        $this->linkedArticles = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getWord(): ?string
    {
        return $this->word;
    }

    public function setWord(?string $word): self
    {
        $this->word = $word;

        return $this;
    }

    /**
     * @return Collection|Article[]
     */
    public function getLinkedArticles(): Collection
    {
        return $this->linkedArticles;
    }

    public function addLinkedArticle(Article $linkedArticle): self
    {
        if (!$this->linkedArticles->contains($linkedArticle)) {
            $this->linkedArticles[] = $linkedArticle;
            $linkedArticle->setLexicalIndex($this);
        }

        return $this;
    }

    public function removeLinkedArticle(Article $linkedArticle): self
    {
        if ($this->linkedArticles->contains($linkedArticle)) {
            $this->linkedArticles->removeElement($linkedArticle);
            // set the owning side to null (unless already changed)
            if ($linkedArticle->getLexicalIndex() === $this) {
                $linkedArticle->setLexicalIndex(null);
            }
        }

        return $this;
    }

    public function getMetaphone(): ?string
    {
        return $this->metaphone;
    }

    public function setMetaphone(?string $metaphone): self
    {
        $this->metaphone = $metaphone;

        return $this;
    }
}
