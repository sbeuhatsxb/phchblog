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


class IndexArticleActionService
{

    const PREPOSITIONS = ["dans", "de", "en", "jusque", "jusqu'", "par", "sur"];
    const CONJONCTIONS = ["et"];
    const DETERMINANTS = ["le", "la", "les", "l'", "un", "une", "des", "du", "au", "aux", "son", "sa", "ses", "ce", "cet", "cette"];
    const SYMBOLES = [".", ",", ";", ":", "?", "!", "(", ")", "[", "]", "§", "<", ">", "&"];

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

    public function indexArticle()
    {

        $articles = $this->articleRepo->findAll();
        $i = 0;

        foreach ($articles as $article) {
            $metaphoneArticle = new ArticleMetaphone();

            $metaphoneArticle->setLinkedArticle($article);
            $splitedContent = substr($article->getContent(), 0, 16383);
            $metaphoneArticle->setMetaphoneArticle(metaphone($splitedContent));
            $this->entityManager->persist($metaphoneArticle);
            if ($i >= 20) {
                $this->entityManager->flush();
            }
            $i++;
        }

        $this->entityManager->flush();
    }

    public function lexicalIndexArticle()
    {

        $articles = $this->articleRepo->findAll();
        $i = 0;


        foreach ($articles as $article) {
            $dictionnary = [];
            $articleStr = $article->getContent();
            $articleArray = explode(" ", $articleStr);
            foreach ($articleArray as $word) {
                $word = strtolower($word);

                //excluding words begining with /
                if (substr($word, 0, 1) === "/") {
                    continue;
                    //excluding words begining with <
                } elseif (substr($word, 0, 1) === "<") {
                    continue;
                } elseif (substr($word, 0, 1) === "&") {
                    continue;
                } else {
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
            }

            foreach ($dictionnary as $entry) {

                $wordExist = $this->lexicalIndexRepo->findOneBy(['word' => $entry]);
                if (!isset($wordExist)) {
                    $lexicalIndex = new LexicalIndex();

                    $lexicalIndex->addLinkedArticle($article);
                    $lexicalIndex->setWord($entry);
                    $lexicalIndex->setMetaphone(metaphone($entry));
                    $this->entityManager->persist($lexicalIndex);
                } else {
                    $wordExist->addLinkedArticle($article);
                    $this->entityManager->persist($wordExist);
                }

                $i++;
                if ($i >= 50) {
                    $this->entityManager->flush();
                    $i = 0;
                }

            }
            $this->entityManager->flush();
        }

    }
}