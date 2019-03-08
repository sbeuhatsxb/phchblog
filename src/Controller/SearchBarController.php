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
use App\Service\IndexArticleService;
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
use Twig_Extension_StringLoader;
use Twig_SimpleFilter;


class SearchBarController extends Controller
{
    const HIGHLIGHT = '#FFFF00';

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

            $articlesArray = $this->searchIndexedArticles->getArticlesFromSubmit($filterArray);

            $this->feedPreview($articlesArray, $filterArray);

            if (is_null($articlesArray)) {
                throw new NotFoundHttpException('Aucun résultat selon les critères sélectionnés...');
            }

            $articles = $this->paginationService->paginate($articlesArray, 1, 12);

            return $this->render('article_list.html.twig', [
                'articles' => $articles,
                'searchedTermArray' => $filterArray
            ]);

        }

        throw new NotFoundHttpException('Le requête n\'est pas valide : la soumission du formulaire ou sa validation ont échoué');
    }


    /**
     * @param $articlesArray
     * @param $filterArray
     */
    private function feedPreview($articlesArray, $filterArray)
    {

        foreach ($articlesArray as $articleObject) {

            $article = $this->utf8Conv($articleObject->getContent());

            $articleExploded = explode(" ", $article);

            $firstOccurence = true;

            foreach ($filterArray as $filter) {

                $stringPos = array_search(mb_strtolower($filter), $articleExploded);

                if ($stringPos > 0 && $firstOccurence) {

                    $wordBeforeArray = $this->getWordsBeforeOrAfter($stringPos, $articleExploded, 20, "-");
                    $wordAfterArray = $this->getWordsBeforeOrAfter($stringPos, $articleExploded, 50);

                    $strBefore = implode(" ", array_reverse($wordBeforeArray));
                    $strMiddle = '<span style="background-color: ' . self::HIGHLIGHT . '">' . $filter . '</span>';
                    $strAfter = implode(" ", $wordAfterArray);

                    $preview = $strBefore . " " . $strMiddle . " " . $strAfter;
                    $firstOccurence = false;
                }
                if ($firstOccurence == false) {
                    $strMiddle = '<span style="background-color: ' . self::HIGHLIGHT . '">' . $filter . '</span>';
                    $preview = str_ireplace($filter, $strMiddle, $preview);
                }
            }
            //To spare some code we setContent here. Since setPreview is not configured in Twig and would make some redundant code.
            if (isset($preview)) {
                $articleObject->setContent($preview);
            }
        }
    }

    /**
     * @param $article
     * @return false|mixed|string|string[]|null
     */
    private function utf8Conv($article)
    {
        //UTF-8 conversion
        $article = mb_convert_encoding($article, 'UTF-8');
        //Decoding remainings HTML codes
        $article = html_entity_decode($article);
        //Uncapitalize word
        $article = mb_strtolower($article);

        return $article;
    }

    /**
     * @param $stringPos
     * @param $articleExploded
     * @param $occurence
     * @param string $sign
     * @return array
     */
    private function getWordsBeforeOrAfter($stringPos, $articleExploded, $occurence, $sign = "+")
    {
        for ($i = 1; $i <= $occurence; $i++) {
            if ($sign == "+") {
                $termPos = $stringPos + $i;
            } else {
                $termPos = $stringPos - $i;
            }

            if (isset($articleExploded[$termPos])) {

                if (substr($articleExploded[$termPos], 0, 1) === "<") {

                    $beginningPos = strpos($articleExploded[$termPos], '<');
                    $endPos = strpos($articleExploded[$termPos], '>');

                    $textToDelete = substr($articleExploded[$termPos], $beginningPos, ($endPos + strlen('>')) - $beginningPos);
                    $wordAfterArray[] = str_replace($textToDelete, '', $articleExploded[$termPos]);

                } else {
                    $wordAfterArray[] = $articleExploded[$termPos];
                }

            }
        }
        return $wordAfterArray;
    }

}