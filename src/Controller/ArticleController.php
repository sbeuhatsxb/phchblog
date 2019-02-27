<?php

namespace App\Controller;

use App\Entity\Article;
use App\Entity\Author;
use App\Entity\Category;
use App\Entity\Concept;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/article")
 */
class ArticleController extends AbstractController
{

    /**
     * @Route("/{id}", name="article_show", methods={"GET"})
     * @param Article $article
     * @return Response
     * @throws \ReflectionException
     */
    public function show(Article $article): Response
    {
        $concept = new \ReflectionClass(Concept::class);
        //required for class name
        $conceptClass = $concept->getName();
        //required for path
        $conceptShortname = $concept->getShortName();

        $author = new \ReflectionClass(Author::class);
        $authorClass = $author->getName();
        $authorShortname = $author->getShortName();

        $category = new \ReflectionClass(Category::class);
        $categoryClass = $category->getName();
        $categoryShortname = $category->getShortName();


        return $this->render('article/show.html.twig', [
            'article' => $article,
            'conceptClass' => $conceptClass,
            'authorClass' => $authorClass,
            'categoryClass' => $categoryClass,
            'conceptShortname' => $conceptShortname,
            'authorShortname' => $authorShortname,
            'categoryShortname' => $categoryShortname,

        ]);
    }
}
