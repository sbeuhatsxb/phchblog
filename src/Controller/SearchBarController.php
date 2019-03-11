<?php
/**
 * Created by PhpStorm.
 * User: sebastien
 * Date: 01/03/19
 * Time: 14:38
 */

namespace App\Controller;

use App\Entity\LexicalIndex;
use App\Service\CreatePreviewArticleService;
use App\Service\IndexArticleService;
use App\Service\PaginationService;
use App\Service\SearchAndScoreIndexedArticleService;
use Doctrine\ORM\EntityManagerInterface;
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
     * @var CreatePreviewArticleService $createPreviewArticleService
     */
    protected $createPreviewArticleService;

    /**
     * @var PaginationService
     */
    protected $paginationService;

    /**
     * @var SearchAndScoreIndexedArticleService
     */
    protected $searchIndexedArticles;


    public function __construct(CreatePreviewArticleService $createPreviewArticleService, SearchAndScoreIndexedArticleService $searchIndexedArticles, PaginationService $paginationService)
    {
        $this->createPreviewArticleService = $createPreviewArticleService;
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

            //Get articles from the submit
            $articlesArray = $this->searchIndexedArticles->getArticlesFromSubmit($filterArray);
            if (empty($articlesArray)) {
                throw new NotFoundHttpException('Aucun résultat selon les critères sélectionnés...');
            }

            //Populate $content ($preview) with an extract of the paragraph in which the term is looked for.
            $this->createPreviewArticleService->feedPreview($articlesArray, $filterArray);

            $articles = $this->paginationService->paginate($articlesArray, 1, 12);

            return $this->render('article_list.html.twig', [
                'articles' => $articles,
                'searchedTermArray' => $filterArray
            ]);

        }

        throw new NotFoundHttpException('Le requête n\'est pas valide : la soumission du formulaire ou sa validation ont échoué');
    }


    function cmp($a, $b)
    {
        return strcmp($a->score, $b->score);
    }

}