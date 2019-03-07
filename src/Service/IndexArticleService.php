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


class IndexArticleService
{

    const CODES = [ "&amp;", "&nbsp;", "/p&gt;", "/&gt;"];
    const PREPOSITIONS = ["dans", "de", "en", "jusque", "jusqu'", "par", "sur"];
    const CONJONCTIONS = ["et"];
    const DETERMINANTS = ["le", "la", "les", "l'", "un", "une", "des", "du", "au", "aux", "son", "sa", "ses", "ce", "cet", "cette"];
    const SYMBOLES = [".", ",", ";", ":", "?", "!", "(", ")", "[", "]", "§", "<", ">", "&", "<p", "_"];
    const SYMBOLE_APPERTURES = ["<", "<", "&", "/"];
    const EXCLUDED_STRING = ["href=\"https:", "www"];

    /**
     * @var EntityManagerInterface $entityManager
     */
    protected $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * @param array $article
     */
    public function indexArticle(array $article)
    {
        $this->lexicalIndexArticle($article);
    }

    public function indexAllArticle()
    {
        //Clean the index
        $this->cleaningIndex();

        $articleRepo = $this->entityManager->getRepository(Article::class);
        $articles = $articleRepo->findAll();

        $this->lexicalIndexArticle($articles);
    }


    /**
     * @param array $articles
     */
    public function lexicalIndexArticle(array $articles)
    {

        foreach ($articles as $article) {

            $articleStr = $article->getContent();

            $pattern = '/nbsp;|&amp;|&gt;|&lt;|p&gt;|&nbsp;|<p>|<p>|<br \/>|br \/|\/|\\r|\\n|<\/p>/';
            $replacement = ' ';
            $cleanedArticle = preg_replace($pattern, $replacement, $articleStr);

            $articleArray = explode(" ", $cleanedArticle);

            //Isolate words in the article and add them to an array
            $dictionnary = $this->createDictonnary($articleArray);

            $this->flushEntry($dictionnary, $article);

        }
    }

    private function cleaningIndex()
    {
        //Removing the index
        $LIrepo = $this->entityManager->getRepository(LexicalIndex::class);
        $indexes = $LIrepo->findAll();

        foreach ($indexes as $index) {
            $this->entityManager->remove($index);
        }
        $this->entityManager->flush();
    }

    public function createDictonnary($articleArray)
    {
        //Each article sets its own dictionnary
        $dictionnary = [];

        //Clean the index as much as possible :
        foreach ($articleArray as $word) {

            //UTF-8 Conversion
            $word = $this->utf8Conv($word);

            //Isolate reflecting verbs or articles with vowel ("l'ensemble", "s'affairer"...)
            if (mb_substr($word, 1, 1) === "’") {
                $word = mb_substr($word, 2);
            }

            //Word column limited to 30 char in the index
            $word = $this->wordTruncate($word,29);

            //Excluding numbers
            if(is_numeric($word)){
                continue;
            }

            //Excluding words equal to 0
            if(mb_strlen($word) == 0 || strlen($word) == 0){
                continue;
            }

            //excluding words begining with /
            foreach (self::SYMBOLE_APPERTURES as $sign) {
                if (mb_strpos($word, 0, 1) === $sign) {
                    continue;
                }
            }

            //triming words with symbols
            foreach (self::SYMBOLES as $symbol) {
                if (mb_strpos($word, $symbol)) {
                    //extract the string until you find the first position of the symbol you're looking for
                    $word = $this->wordTrim($word, $symbol);
                }
            }

            //we keep only word >= 3 chars
            if (mb_strlen($word) <= 2) {
                continue;
            }

            //Here we exclude all words which are already belonging to our array or to our CONSTs
            if (!in_array($word, $dictionnary) &&
                !in_array($word, self::DETERMINANTS) &&
                !in_array($word, self::PREPOSITIONS) &&
                !in_array($word, self::EXCLUDED_STRING) &&
                !in_array($word, self::CONJONCTIONS)) {

                $dictionnary[] = $word;
            }
        }

        return $dictionnary;
    }

    private function utf8Conv($word)
    {
        //UTF-8 conversion
        $word = mb_convert_encoding($word, 'UTF-8');
        //Decoding remainings HTML codes
        $word = html_entity_decode($word);
        //Uncapitalize word
        $word = mb_strtolower($word);

        return $word;
    }

    private function wordTruncate($string, $length)
    {
        return (strlen($string) > $length) ? substr($string, 0, $length) : $string;
    }

    private function wordTrim($word, $symbol)
    {
        //extract the string until you find the first position of the symbol you're looking for
        return mb_substr($word, 0, mb_strpos($word, $symbol));
    }

    private function flushEntry($dictionnary, $article)
    {
        $i = 0;

        foreach ($dictionnary as $entry) {


            //Here we check if each word has its entry already in the DB
            $lexicalIndexRepo = $this->entityManager->getRepository(LexicalIndex::class);
            $wordExist = $lexicalIndexRepo->findOneBy(['word' => $entry]);

            //If not we generate this entry
            if (!isset($wordExist)) {
                $lexicalIndex = new LexicalIndex();

                $lexicalIndex->addLinkedArticle($article);
                $lexicalIndex->setWord($entry);
                $lexicalIndex->setMetaphone(metaphone($entry));
                $this->entityManager->persist($lexicalIndex);
            } else {
                //Else we simply add a new article linked to this entry
                $wordExist->addLinkedArticle($article);
                $this->entityManager->persist($wordExist);
            }

            //Flushing DB every $i element :
            if ($i >= 50) {
                $this->entityManager->flush();
                $i = 0;
            }
            $i++;

        }
        $this->entityManager->flush();
    }
}