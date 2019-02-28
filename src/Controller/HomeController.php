<?php
/**
 * Created by PhpStorm.
 * User: sebastien
 * Date: 22/02/19
 * Time: 13:53
 */

namespace App\Controller;

use App\Entity\Article;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Service\LastArticlesService;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\Concept;
use App\Entity\Category;
use App\Entity\Author;


class HomeController extends Controller
{

    /**
     * @var LastArticlesService
     */
    protected $lastArticlesService;

    public function __construct(LastArticlesService $lastArticlesService)
    {
        $this->lastArticlesService = $lastArticlesService;
    }

    /**
     * @Route("/",  name="index")
     */
    public function index()
    {
        $lastArticles = $this->lastArticlesService->getLastArticles();

        return $this->render('article_list.html.twig', [
            'articles' => $lastArticles,
        ]);
    }

    /**
     * @Route("/{classname}/{shortname}/{filter}",  name="filtered_list")
     * @param $classname
     * @param $filter
     * @param $shortname
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function filteredList($classname, $filter, $shortname, Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $getFilter = $em->getRepository($classname)->findBy(['name' => $filter]);

        $filterId = $getFilter[0]->getId();

        $lastArticles = $this->lastArticlesService->getLastArticles($shortname, $filterId);


        /* @var $paginator \Knp\Component\Pager\Paginator */
        $paginator  = $this->get('knp_paginator');

        // Paginate the results of the query
        $articles = $paginator->paginate(
            // Doctrine Query, not results
            $lastArticles,
            // Define the page parameter
            $request->query->getInt('page', 1),
            // Items per page
            12
        );

        return $this->render('article_list.html.twig', [
            'articles' => $articles,
            'shortname' => $shortname,
            'filter' => $filter
        ]);
    }

}