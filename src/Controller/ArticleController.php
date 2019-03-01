<?php

namespace App\Controller;

use App\Entity\Article;
use App\Entity\Author;
use App\Entity\Category;
use App\Entity\Concept;
use App\Form\SearchFormType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\FormView;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/article")
 */
class ArticleController extends Controller
{

    /**
     * @Route("/{id}", name="article_show", methods={"GET"})
     * @param Article $article
     * @param Request $request
     * @return Response
     * @throws \ReflectionException
     */
    public function show(Article $article, Request $request): Response
    {
        $concept = new \ReflectionClass(Concept::class);
        //required for class name
        $conceptClass = $concept->getName();
        //required for path
        $conceptShortname = $concept->getShortName();

        $author = new \ReflectionClass(Author::class);
        //ibid
        $authorClass = $author->getName();
        //ibid
        $authorShortname = $author->getShortName();

        $category = new \ReflectionClass(Category::class);
        //ibid
        $categoryClass = $category->getName();
        //ibid
        $categoryShortname = $category->getShortName();

        $form = $this->createForm(SearchFormType::class);
        $form->handleRequest($request);


        return $this->render('article/show.html.twig', [
            'article' => $article,
            'conceptClass' => $conceptClass,
            'authorClass' => $authorClass,
            'categoryClass' => $categoryClass,
            'conceptShortname' => $conceptShortname,
            'authorShortname' => $authorShortname,
            'categoryShortname' => $categoryShortname,
            'form'=> $form->createView()
        ]);
    }
}
