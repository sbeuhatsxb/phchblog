<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

// * @ORM\Table(indexes={
// *  @ORM\Index(name="metaphone_article_idx", columns={"metaphone_article"})
// * })


/**
 * @ORM\Entity(repositoryClass="App\Repository\ArticleMetaphoneRepository")
 */
class ArticleMetaphone
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $metaphoneArticle;

    /**
     * @ORM\OneToOne(targetEntity="App\Entity\Article", inversedBy="articleMetaphone", cascade={"persist", "remove"})
     */
    private $linkedArticle;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getMetaphoneArticle(): ?string
    {
        return $this->metaphoneArticle;
    }

    public function setMetaphoneArticle(?string $metaphoneArticle): self
    {
        $this->metaphoneArticle = $metaphoneArticle;

        return $this;
    }

    public function getLinkedArticle(): ?Article
    {
        return $this->linkedArticle;
    }

    public function setLinkedArticle(?Article $linkedArticle): self
    {
        $this->linkedArticle = $linkedArticle;

        return $this;
    }
}
