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

    const PREPOSITIONS = ["dans", "de", "en", "jusque", "jusqu'", "par", "sur"];
    const CONJONCTIONS = ["et"];
    const DETERMINANTS = ["le", "la", "les", "l'", "un", "une", "des", "du", "au", "aux", "son", "sa", "ses", "ce", "cet", "cette"];
    const SYMBOLES = [".", ",", ";", ":", "?", "!", "(", ")", "[", "]", "§", "<", ">", "&"];
    const SYMBOLE_APPERTURES = ["<", "&", "/"];

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
            $articleArray = explode(" ", $articleStr);

            //Clean the index as much as possible :
            foreach ($articleArray as $word) {
                $word = strtolower($word);

                //excluding words begining with /
                foreach (self::SYMBOLE_APPERTURES as $sign) {
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

                //Here we exclude all words which are already belonging to our array or to our CONSTs
                if (!in_array($word, $dictionnary) &&
                    !in_array($word, self::DETERMINANTS) &&
                    !in_array($word, self::PREPOSITIONS) &&
                    !in_array($word, self::CONJONCTIONS)) {
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