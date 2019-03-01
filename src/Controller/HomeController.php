<?php
/**
 * Created by PhpStorm.
 * User: sebastien
 * Date: 22/02/19
 * Time: 13:53
 */

namespace App\Controller;

use App\Form\SearchFormType;
use App\Service\SearchBarFormService;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Routing\Annotation\Route;
use App\Service\LastArticlesService;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use App\Entity\Article;
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
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function index(Request $request)
    {

        $lastArticles = $this->lastArticlesService->getLastArticles();

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

        if($articles->getTotalItemCount() == 0){
                throw new NotFoundHttpException('Aucun résultat selon les critères sélectionnés...');
        };

        return $this->render('article_list.html.twig', [
            'articles' => $articles,
        ]);
    }

    /**
     * @Route("/filtres/{classname}/{shortname}/{filter}",  name="filtered_list")
     * @param $classname
     * @param $filter
     * @param $shortname
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function filteredList($classname = null, $filter = null, $shortname = null, Request $request)
    {

        $em = $this->getDoctrine()->getManager();

        $getFilter = $em->getRepository($classname)->findBy(['name' => $filter]);

        if(!class_exists($classname)
            || !in_array($shortname, ['Concept', 'Category', "Author"])
            || empty($getFilter)
            ){
            throw new NotFoundHttpException('Désolé, ce filtre n\'existe pas...');
        }

        $filterId = $getFilter[0]->getId();

        $lastArticles = $this->lastArticlesService->getLastArticles($shortname, $filterId);

        /* @var $paginator \Knp\Component\Pager\Paginator */
        $paginator  = $this->get('knp_paginator');

        $articles = $paginator->paginate(
            $lastArticles,
            $request->query->getInt('page', 1),
            // Items per page
            12
        );

        if($articles->getTotalItemCount() == 0){
            throw new NotFoundHttpException('Aucun résultat selon les critères sélectionnés...');
        };

        return $this->render('article_list.html.twig', [
            'articles' => $articles,
            'shortname' => $shortname,
            'filter' => $filter,

        ]);
    }


}