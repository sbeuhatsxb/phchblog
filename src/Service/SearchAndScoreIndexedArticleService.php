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
use phpDocumentor\Reflection\Types\Boolean;


class SearchAndScoreIndexedArticleService
{

    /**
     * Here we try to make sure that scoring a metaphone research won't overlap an exact match
     * Otherwise we'd rather a FullWord over an extract of a string
     * And if a term is repeated in an article, we might consider this article to be more important about this term
     */
    const POLY_EXACT_MATCH = 1000000;
    const POLY_APPROX_MATCH = 1000;
    const FULL_WORD = 10000;
    const REPETITION = 2000;
    const PONCTUATION = [" ", ".", ",", ";", ":", "!", "?", "%", "\"", "<"];


    /**
     * @var LexicalIndexRepository $lexicalIndexRepository
     */
    protected $lexicalIndexRepository;

    public function __construct(LexicalIndexRepository $lexicalIndexRepository)
    {
        $this->lexicalIndexRepository = $lexicalIndexRepository;
    }

    /**
     * @param array $filterArray
     * @return array
     */
    public function getArticlesFromSubmit(array $filterArray)
    {
        /**
         *[[[[[ EXTRACTING ARTICLES FROM DB ]]]]]
         * filter = term
         */
        $articlesArray = [];
        $inWord = false;

        foreach ($filterArray as $filter) {

            /**
             *Get result for an APPROXIMATIVE correspondance
             * Begin with those to stakc them to the end of the pile
             */
            $approx = true;
            $qb = $this->lexicalIndexRepository->findWithApproxTerm($filter, $inWord);
            //Simple research where the term is a word and is correctly spelled
            if ($this->getQbResult($qb)) {
                $articlesArray = $this->parseAndScoreArticle($qb, $articlesArray, $filter, $inWord, $approx);
            } else {
                // if we don't find any result :
                //    Simple research where the term is IN a word and correctly spelled
                $inWord = true;
                $qb = $this->lexicalIndexRepository->findWithExactTerm($filter, $inWord);

                if ($this->getQbResult($qb)) {
                    $articlesArray = $this->parseAndScoreArticle($qb, $articlesArray, $filter, $inWord, $approx);
                }
            }


            /**
             *Get result for an EXACT correspondance
             */
            $qb = $this->lexicalIndexRepository->findWithExactTerm($filter, $inWord);

            //Simple research where the term is a word and is correctly spelled
            if ($this->getQbResult($qb)) {
                $articlesArray = $this->parseAndScoreArticle($qb, $articlesArray, $filter, $inWord);
            } else {
                // if we don't find any result :
                //    Simple research where the term is IN a word and correctly spelled
                $inWord = true;
                $qb = $this->lexicalIndexRepository->findWithExactTerm($filter, $inWord);

                if ($this->getQbResult($qb)) {
                    $articlesArray = $this->parseAndScoreArticle($qb, $articlesArray, $filter, $inWord);
                }
            }
        }
        usort($articlesArray, array($this, "cmp"));

        return array_reverse($articlesArray);

    }

    private function setCurrentScore(Article $article, string $filter, bool $inWord, bool $approx)
    {

        //Get Article score
        if (!$approx) {
            $score = $article->getScore() + self::POLY_EXACT_MATCH;
        } else {
            $score = $article->getScore() + self::POLY_APPROX_MATCH;
        }

        //Here we check if the searched term is a word or some characters in a word
        foreach (self::PONCTUATION as $ponctuation) {
            if (strpos($article->getContent(), ' ' . $filter . $ponctuation) !== false) {
                $inWord = true;
                continue;
            }
        }

        if (!$inWord) {
            $score += $this->countOccurence($article, $filter) * self::FULL_WORD;
        } else {
            $score += $this->countOccurence($article, $filter, $inWord) * self::REPETITION;
        }

        $article->setScore($score);
    }

    private function getQbResult($qb)
    {
        return $qb->getQuery()->getResult();
    }

    private function getLinkedArticleArrays($qb)
    {
        $indexCollection = $qb->getQuery()->getResult();
        foreach ($indexCollection as $index) {
            /**
             * @var LexicalIndex $index
             */
            $indexArray[] = $index->getLinkedArticle();
        }
        return $indexArray;
    }

    private function countOccurence(Article $article, string $filter, bool $inWord = false)
    {
        if (!$inWord) {
            return substr_count($article->getContent(), ' ' . $filter . ' ');
        } else {
            return substr_count($article->getContent(), $filter);
        }
    }

    private function parseAndScoreArticle($qb, $articlesArray, $filter, $inWord = false, $approx = false)
    {
        $linkedArticlesArrayCollection[] = $this->getLinkedArticleArrays($qb);

        foreach ($linkedArticlesArrayCollection as $linkedArticlesArray) {
            foreach ($linkedArticlesArray as $linkedArticles) {
                foreach ($linkedArticles as $article) {
                    /**
                     * @var Article $article
                     */
                    if (in_array($article, $articlesArray)) {
                        $this->setCurrentScore($article, $filter, $inWord, $approx);
                    } else {
                        $this->setCurrentScore($article, $filter, $inWord, $approx);
                        $articlesArray[] = $article;
                    }
                }
            }
        }
        return $articlesArray;
    }

    function cmp($a, $b)
    {
        return strcmp($a->score, $b->score);
    }

}