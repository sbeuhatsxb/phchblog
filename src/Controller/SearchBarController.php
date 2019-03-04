<?php
/**
 * Created by PhpStorm.
 * User: sebastien
 * Date: 01/03/19
 * Time: 14:38
 */

namespace App\Controller;

use App\Entity\Article;
use App\Entity\LexicalIndex;
use App\Service\IndexArticleActionService;
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

            $articlesArray = [];

            //Comparing query to database index #word
            $exactLexicalIndexesReturned = $this->searchIndexedArticles->getArticlesFromExactSubmit($filterArray)->getQuery()->getResult();
            if(count($exactLexicalIndexesReturned) > 0 ){
                /**
                 * @var LexicalIndex $lexicalIndex
                 */
                foreach ($exactLexicalIndexesReturned as $lexicalIndex){
                    dd($lexicalIndex->getLinkedArticle()->count());
                    foreach($lexicalIndex->getLinkedArticle() as $article){
                        dump($article);
                        if(!in_array($article, $articlesArray)){
                            $articlesArray[] = $article;
                        }
                    };
                    dd($articlesArray);
                }
            } else {
                //Comparing query to database index #metaphone
                $approximateLexicalIndexesReturned = $this->searchIndexedArticles->getArticlesFromApproximalSubmit($filterArray)->getQuery()->getResult();

                /**
                 * @var LexicalIndex $lexicalIndex
                 */
                foreach ($approximateLexicalIndexesReturned as $lexicalIndex){
                    foreach($lexicalIndex->getLinkedArticle() as $article){
                        if(!in_array($article, $articlesArray)){
                            $articlesArray[] = $article;
                        }
                    };
                }

            }





            $articles = $this->paginationService->paginate($articlesArray, 1, 12);

            if ($articles->getTotalItemCount() == 0) {

                throw new NotFoundHttpException('Aucun résultat selon les critères sélectionnés...');

            };

            return $this->render('article_list.html.twig', [
                'articles' => $articles,
            ]);

        }
    }

}