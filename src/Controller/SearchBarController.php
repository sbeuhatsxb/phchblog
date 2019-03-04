<?php
/**
 * Created by PhpStorm.
 * User: sebastien
 * Date: 01/03/19
 * Time: 14:38
 */

namespace App\Controller;

use App\Service\LastArticlesService;
use App\Service\PaginationService;
use App\Service\SearchIndexedArticleService;
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
     * @var PaginationService
     */
    protected $paginationService;

    /**
     * @var SearchIndexedArticleService
     */
    protected $searchIndexedArticles;

    public function __construct(SearchIndexedArticleService $searchIndexedArticles, PaginationService $paginationService)
    {
        $this->searchIndexedArticles = $searchIndexedArticles;
        $this->paginationService = $paginationService;
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

            $filterArray = explode(" ", $filter['search']);


            $queriedArticles = $this->searchIndexedArticles->getArticlesFromSubmit($filterArray)->getQuery()->getResult();

            $articlesArray = [];

            foreach ($queriedArticles as $queriedArticle){
                $articlesArray[] = $queriedArticle->getLinkedArticle();
            }

            $articles = $this->paginationService->paginate($articlesArray, 1, 12);

            if ($articles->getTotalItemCount() == 0) {
//                while (count($filterArray) != 1) {
//                    array_pop($filterArray);
//
//                    $queriedArticles = $this->lastArticlesService->getArticlesFromSubmit($filterArray);
//                    $articles = $this->paginationService->paginate($queriedArticles, 1, 12);
//
//                    if ($articles->getTotalItemCount() != 0) {
//                        return $this->render('article_list.html.twig', [
//                            'articles' => $articles,
//                        ]);
//                    }
//                }

                throw new NotFoundHttpException('Aucun résultat selon les critères sélectionnés...');

            };

            return $this->render('article_list.html.twig', [
                'articles' => $articles,
            ]);

        }
    }

}