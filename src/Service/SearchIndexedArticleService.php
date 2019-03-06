<?php
/**
 * Created by PhpStorm.
 * User: sebastien
 * Date: 03/03/19
 * Time: 18:04
 */

namespace App\Service;

use App\Entity\Article;
use App\Entity\LexicalIndex;
use App\Repository\LexicalIndexRepository;


class SearchIndexedArticleService
{
    /**
     * @var LexicalIndexRepository $lexicalIndexRepository
     */
    protected $lexicalIndexRepository;

    public function __construct(LexicalIndexRepository $lexicalIndexRepository)
    {
        $this->lexicalIndexRepository = $lexicalIndexRepository;
    }

    /**
     * This method tries to match to an existing and exact term into the DB
     * If this specific term was not found, it looks for the metaphone version of this term
     * If there is any result, it scores the articles according to the number of occurrences of the searched term(s) into the article
     * Then we sort the articleArray according to its score
     *
     * @param $filterArray
     * @return array
     */
    public function getArticlesFromSubmit($filterArray)
    {
        /**
         *[[[[[ EXTRACTING ARTICLES FROM DB ]]]]]
         */
        $articlesArray = [];


        //Comparing query to database index #word
        $exactLexicalIndexesReturned = $this->lexicalIndexRepository->findFilteredArticlesByExactForm($filterArray)->getQuery()->getResult();
        if (count($exactLexicalIndexesReturned) > 0) {
            /**
             * @var LexicalIndex $lexicalIndex
             */
            foreach ($exactLexicalIndexesReturned as $lexicalIndex) {
                foreach ($lexicalIndex->getLinkedArticle() as $article) {
                    if (!in_array($article, $articlesArray)) {
                        $articlesArray[] = $article;
                    }
                };
            }
        } else {
            //Comparing query to database index #metaphone
            $approximateLexicalIndexesReturned = $this->lexicalIndexRepository->findFilteredArticlesByApproximalForm($filterArray)->getQuery()->getResult();
            if (count($approximateLexicalIndexesReturned) > 0) {
                /**
                 * @var LexicalIndex $lexicalIndex
                 */
                foreach ($approximateLexicalIndexesReturned as $lexicalIndex) {
                    foreach ($lexicalIndex->getLinkedArticle() as $article) {
                        if (!in_array($article, $articlesArray)) {
                            $articlesArray[] = $article;
                        }
                    };
                }
            } else {
                //If no result we simply return null
                return null;
            }

        }

        /**
         *[[[[[ SCORING ARTICLES ]]]]]
         * @var Article $article
         */
        $unreasonnedValue = 100;
        $unreasonnedValuePerArticle = intval($unreasonnedValue / count($filterArray));
        foreach ($articlesArray as $article) {
            foreach ($filterArray as $filter) {
                //Count the occurrences of a term in the article
                $occurrenceNb = substr_count($article->getContent(), $filter);
                //score the article according to its occurrences
                $score = $occurrenceNb * $unreasonnedValuePerArticle;
                //adding score
                $articleScore = $article->getScore();
                $articleScore += $score;
                $article->setScore($articleScore);
            }
        }

        usort($articlesArray, array($this, "cmp"));

        return array_reverse($articlesArray);
    }


    function cmp($a, $b)
    {
        return strcmp($a->score, $b->score);
    }

}