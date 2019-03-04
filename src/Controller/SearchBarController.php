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


            /**
             *[[[[[ EXTRACTING ARTICLES FROM DB ]]]]]
             */
            $articlesArray = [];

            //Comparing query to database index #word
            $exactLexicalIndexesReturned = $this->searchIndexedArticles->getArticlesFromExactSubmit($filterArray)->getQuery()->getResult();
            if(count($exactLexicalIndexesReturned) > 0 ){
                /**
                 * @var LexicalIndex $lexicalIndex
                 */
                foreach ($exactLexicalIndexesReturned as $lexicalIndex){
                    foreach($lexicalIndex->getLinkedArticle() as $article){
                        if(!in_array($article, $articlesArray)){
                            $articlesArray[] = $article;
                        }
                    };
                }
            } else {
                //Comparing query to database index #metaphone
                $approximateLexicalIndexesReturned = $this->searchIndexedArticles->getArticlesFromApproximalSubmit($filterArray)->getQuery()->getResult();
                if(count($approximateLexicalIndexesReturned) > 0){
                    $approx = true;
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
                } else {
//                    throw new NotFoundHttpException('Aucun résultat selon les critères sélectionnés...');
                }

            }

            /**
             *[[[[[ SCORING ARTICLES ]]]]]
             * @var Article $article
             */
            $unreasonnedValue = 100;
            $unreasonnedValuePerArticle = intval($unreasonnedValue / count($filterArray));
            foreach ($articlesArray as $article){
                foreach ($filterArray as $filter){
                    $occurrenceNb = substr_count($article->getContent(), $filter);
                    $score = $occurrenceNb * $unreasonnedValuePerArticle;
                }
                $orderArticles[] = [$score => $article];
            }


//            $order = array_multisort($scoreArray, $articlesArray);
//            dd($articlesArray);



            $articles = $this->paginationService->paginate($articlesArray, 1, 12);

            return $this->render('article_list.html.twig', [
                'articles' => $articles,
            ]);

        }
    }

}