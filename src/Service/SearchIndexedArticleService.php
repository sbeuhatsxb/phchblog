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


class SearchIndexedArticleService
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
        $score = 0;
        $inWord = false;
        $approx = false;

        foreach ($filterArray as $filter) {
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

            /**
             *Get result for an APPROXIMATIVE correspondance
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
        }
        usort($articlesArray, array($this, "cmp"));
        dd(array_reverse($articlesArray));

        return array_reverse($articlesArray);


    }

    private function setCurrentScore(Article $article, string $filter, bool $inWord, bool $approx)
    {

        //Get Article score
        if(!$approx){
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




    //        $articlesArray = [];


    //Each term is associated with its

    //        //Comparing query to database index #word
    //        $exactLexicalIndexesReturned = $this->lexicalIndexRepository->findFilteredArticlesByExactForm($filterArray)->getQuery()->getResult();
    //        if (count($exactLexicalIndexesReturned) > 0) {
    //            dd($exactLexicalIndexesReturned);
    //
    //            /**
    //             * @var LexicalIndex $lexicalIndex
    //             */
    //            foreach ($exactLexicalIndexesReturned as $lexicalIndex) {
    //                foreach ($lexicalIndex->getLinkedArticle() as $article) {
    //                    if (!in_array($article, $articlesArray)) {
    //                        $articlesArray[] = $article;
    //                    }
    //                };
    //            }
    //        } else {
    //            //Comparing query to database index #metaphone
    //            $approximateLexicalIndexesReturned = $this->lexicalIndexRepository->findFilteredArticlesByApproximalForm($filterArray)->getQuery()->getResult();
    //            if (count($approximateLexicalIndexesReturned) > 0) {
    //                dd($exactLexicalIndexesReturned);
    //                /**
    //                 * @var LexicalIndex $lexicalIndex
    //                 */
    //                foreach ($approximateLexicalIndexesReturned as $lexicalIndex) {
    //                    foreach ($lexicalIndex->getLinkedArticle() as $article) {
    //                        if (!in_array($article, $articlesArray)) {
    //                            $articlesArray[] = $article;
    //                        }
    //                    };
    //                }
    //
    //
    //            } else {
    //                //If no result we simply return null
    //                return null;
    //            }
    //
    //        }


    //        /**
    //         *[[[[[ SCORING ARTICLES ]]]]]
    //         * @var Article $article
    //         */
    //        $unreasonnedValue = 100;
    //        $unreasonnedValuePerArticle = intval($unreasonnedValue / count($filterArray));
    //        foreach ($articlesArray as $article) {
    //            foreach ($filterArray as $filter) {
    //                //Count the occurrences of a term in the article
    //                $occurrenceNb = substr_count($article->getContent(), $filter);
    //                //score the article according to its occurrences
    //                $score = $occurrenceNb * $unreasonnedValuePerArticle;
    //                //adding score
    //                $articleScore = $article->getScore();
    //                $articleScore += $score;
    //                $article->setScore($articleScore);
    //            }
    //        }
    //
    //        usort($articlesArray, array($this, "cmp"));
    //
    //        return array_reverse($articlesArray);
    //    }




    //    /**
    //     * @param $lexicalIndexesReturned
    //     */
    //    private function getArticlesFromQuery($lexicalIndexesReturned){
    //
    //        $articlesArray = [];
    //        /**
    //         * @var LexicalIndex $lexicalIndex
    //         */
    //        foreach ($lexicalIndexesReturned as $lexicalIndex) {
    //            foreach ($lexicalIndex->getLinkedArticle() as $article) {
    //                if (!in_array($article, $articlesArray)) {
    //                    $articlesArray[] = $article;
    //                }
    //            };
    //        }
    //
    //        return $articlesArray;
    //
    //    }

}