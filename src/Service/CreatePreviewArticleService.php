<?php
/**
 * Created by PhpStorm.
 * User: sebastien
 * Date: 03/03/19
 * Time: 18:38
 */

namespace App\Service;

use App\Entity\Article;
use App\Entity\LexicalIndex;
use App\Repository\ArticleRepository;
use App\Repository\LexicalIndexRepository;
use Doctrine\ORM\EntityManagerInterface;


class CreatePreviewArticleService extends IndexArticleService
{
    const HIGHLIGHT = '#FFFF00';

    /**
     * @var EntityManagerInterface $entityManager
     */
    protected $entityManager;

    function __construct(EntityManagerInterface $entityManager)
    {
        parent::__construct($entityManager);
    }

    /**
     * @param $articlesArray
     * @param $filterArray
     */
    public function feedPreview($articlesArray, $filterArray)
    {
        foreach ($articlesArray as $articleObject) {

            /**
             * @var Article $articleObject
             */
            $article = $this->utf8Conv($articleObject->getContent());

            $articleExploded = explode(" ", $article);

            $firstOccurence = true;
            $preview = null;

            foreach ($filterArray as $filter) {

                $stringPos = null;
                $stringPos = array_search($filter, $articleExploded);

                if ($stringPos > 0 && $firstOccurence) {
                    $wordBeforeArray = $this->getWordsBeforeOrAfter($stringPos, $articleExploded, 20, "-");
                    $wordAfterArray = $this->getWordsBeforeOrAfter($stringPos, $articleExploded, 50);

                    $strBefore = implode(" ", array_reverse($wordBeforeArray));
                    $strMiddle = '<span style="background-color: ' . self::HIGHLIGHT . '">' . $articleExploded[$stringPos] . '</span>';
                    $strAfter = implode(" ", $wordAfterArray);

                    $preview = $strBefore . " " . $strMiddle . " " . $strAfter;

                    $firstOccurence = false;
                } elseif ($firstOccurence == false) {

                    $strMiddle = '<span style="background-color: ' . self::HIGHLIGHT . '">' . $filter . '</span>';
                    $preview = str_ireplace($filter, $strMiddle, $articleObject->getContent());
                }
                if (isset($preview)) {
                    $articleObject->setContent($preview);
                } else {
                    $metaFilter = metaphone($filter);

                    $indexRepo = $this->entityManager->getRepository(LexicalIndex::class);
                    $indexedMetaphoneWord = $indexRepo->findOneBy(["metaphone" => $metaFilter]);
                    if (null !== $indexedMetaphoneWord) {
                        $metaphoneArticlesLinked = $indexedMetaphoneWord->getLinkedArticle();

                        foreach ($metaphoneArticlesLinked as $indexedArticle) {
                            if ($indexedArticle->getId() == $articleObject->getId()) {
                                $wordIndexed[] = $indexedMetaphoneWord->getWord();

                                if (count($wordIndexed) == 1) {
                                    $content = "<i>La recherche approximative estime que le terme : </i>";
                                    foreach ($wordIndexed as $word) {
                                        $content .= '<span style="background-color: ' . self::HIGHLIGHT . '">' . $word . '</span>, ';
                                    }
                                    $content .= "<i>présent dans cet article, correspond à votre requête.</i>";
                                } else {
                                    $content = "<i>La recherche approximative estime que les termes suivants : </i>";
                                    foreach ($wordIndexed as $word) {
                                        $content .= '<span style="background-color: ' . self::HIGHLIGHT . '">' . $word . '</span>' . ', ';
                                    }
                                    $content .= "<i>présents dans cet article correspondent à votre requête.</i>";
                                }

                                $articleObject->setContent($content);
                                continue;
                            } else {
                                return null;
                            }
                        }
                    } else {
                        return null;
                    }
                }
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
                    $wordArray[] = str_replace($textToDelete, '', $articleExploded[$termPos]);

                } else {
                    $wordArray[] = $articleExploded[$termPos];
                }

            }
        }
        return $wordArray;
    }

}