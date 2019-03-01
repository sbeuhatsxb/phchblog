<?php
/**
 * Created by PhpStorm.
 * User: sebastien
 * Date: 01/03/19
 * Time: 14:38
 */

namespace App\Controller;

use App\Service\LastArticlesService;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use App\Service\SearchBarFormService;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;


class SearchBarController extends Controller
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
     * @Route("/resultats", name="searchbar")
     * @Method({"POST"})
     * @param SearchBarFormService $searchBarFormService
     * @param Request $request
     * @return Response
     */
    public function search(SearchBarFormService $searchBarFormService, Request $request)
    {

        $form = $searchBarFormService->getForm();
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $filter = $form->getData();
            $lastArticles = $this->lastArticlesService->getArticlesFromSubmit($filter['search']);
            /* @var $paginator \Knp\Component\Pager\Paginator */
            $paginator = $this->get('knp_paginator');

            // Paginate the results of the query
            $articles = $paginator->paginate(
            // Doctrine Query, not results
                $lastArticles,
                // Define the page parameter
                $request->query->getInt('page', 1),
                // Items per page
                12
            );

            if ($articles->getTotalItemCount() == 0) {
                throw new NotFoundHttpException('Aucun résultat selon les critères sélectionnés...');
            };

            return $this->render('article_list.html.twig', [
                'articles' => $articles,
            ]);

        }
    }
}