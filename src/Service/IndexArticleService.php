<?php
/**
 * Created by PhpStorm.
 * User: sebastien
 * Date: 03/03/19
 * Time: 18:38
 */

namespace App\Service;

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
    const SYMBOLES = [".", ",", ";", ":", "?", "!", "(", ")", "[", "]", "§", "<", ">", "&", "<p"];
    const SYMBOLE_APPERTURES = ["<", "<", "&", "/"];

    /**
     * @var EntityManagerInterface $entityManager
     */
    protected $entityManager;

    /**
     * @var ArticleRepository $articleRepo
     */
    protected $articleRepo;

    /**
     * @var LexicalIndexRepository $lexicalIndexRepo
     */
    protected $lexicalIndexRepo;


    public function __construct(EntityManagerInterface $entityManager, ArticleRepository $articleRepo, LexicalIndexRepository $lexicalIndexRepo)
    {
        $this->entityManager = $entityManager;
        $this->articleRepo = $articleRepo;
        $this->lexicalIndexRepo = $lexicalIndexRepo;
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

        $articles = $this->articleRepo->findAll();
        $this->lexicalIndexArticle($articles);
    }


    /**
     * @param array $articles
     */
    public function lexicalIndexArticle(array $articles)
    {
        //Creating the index
        $i = 0;

        foreach ($articles as $article) {

            //Each article sets its own dictionnary
            $dictionnary = [];

            $articleStr = $article->getContent();

            $pattern = '/nbsp;|&amp;|&gt;|&lt;|p&gt;|&nbsp;|<p>|<p>|<br \/>|br \/|\/|\\r|\\n|<\/p>/';
            $replacement = ' ';
            $cleanedArticle = preg_replace($pattern, $replacement, $articleStr);

            $articleArray = explode(" ", $cleanedArticle);


            //Clean the index as much as possible :
            foreach ($articleArray as $word) {

                $word = mb_convert_encoding($word, 'UTF-8');
                $word = html_entity_decode($word);

                if(strlen($word) == 0){
                    continue;
                } elseif(strlen($word) > 30){
                    //Word column limited to 30 char
                    $word = $this->tokenTruncate($word,30);
                }

                $word = strtolower($word);
                //excluding words begining with /
                foreach (self::SYMBOLE_APPERTURES as $sign) {
//                    dump($sign, $word);
                    if (substr($word, 0, 1) === $sign) {
                        continue;
                    }
                }

                //triming words with symbols
                foreach (self::SYMBOLES as $symbol) {
                    if (strpos($word, $symbol)) {
                        //extract the string until you find the first position of the symbol you're looking for
                        $word = substr($word, 0, strpos($word, $symbol));
                        //removing words in which length is inferior or equal to one
                        if (strlen($word) < 2) {
                            continue;
                        }
                    }
                }

                //we keep only word >= 3 chars
                if (strlen($word) <= 2) {
                    continue;
                }

                //Here we exclude all words which are already belonging to our array or to our CONSTs
                if (!in_array($word, $dictionnary) &&
                    !in_array($word, self::DETERMINANTS) &&
                    !in_array($word, self::PREPOSITIONS) &&
                    !in_array($word, self::CONJONCTIONS)) {
                    if(mb_substr($word, 1, 1) === "’"){
                        $word = mb_substr($word, 2);
                    }
                    dump($word);
                    $dictionnary[] = $word;
                }
            }

            //Each article sets its own dictionnary
            foreach ($dictionnary as $entry) {

                //Here we check if each word has its entry already in the DB
                $wordExist = $this->lexicalIndexRepo->findOneBy(['word' => $entry]);

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
                if ($i >= 100) {
                    $this->entityManager->flush();
                    $i = 0;
                }
                $i++;

            }
            $this->entityManager->flush();
        }
    }

    function tokenTruncate($word, $limit) {
        $parts = preg_split('/([\s\n\r]+)/', $word, null, PREG_SPLIT_DELIM_CAPTURE);
        $parts_count = count($parts);

        $length = 0;
        $last_part = 0;
        for (; $last_part < $parts_count; ++$last_part) {
            $length += strlen($parts[$last_part]);
            if ($length > $limit) { break; }
        }

        return implode(array_slice($parts, 0, $last_part));
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
}