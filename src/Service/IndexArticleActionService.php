<?php
/**
 * Created by PhpStorm.
 * User: sebastien
 * Date: 03/03/19
 * Time: 18:38
 */

namespace App\Service;

use App\Entity\Article;
use App\Entity\ArticleMetaphone;
use App\Repository\ArticleRepository;
use Doctrine\ORM\EntityManagerInterface;


class IndexArticleActionService
{

    /**
     * @var EntityManagerInterface $entityManager
     */
    protected $entityManager;

    /**
     * @var ArticleRepository
     */
    protected $articleRepo;

    public function __construct(EntityManagerInterface $entityManager, ArticleRepository $articleRepo)
    {
        $this->entityManager = $entityManager;
        $this->articleRepo = $articleRepo;
    }

    public function indexArticle(){

        $articles = $this->articleRepo->findAll();
        $i = 0;

        foreach ($articles as $article){
            $metaphoneArticle = new ArticleMetaphone();

            $metaphoneArticle->setLinkedArticle($article);
            $splitedContent = substr($article->getContent(), 0, 16383);
            $metaphoneArticle->setMetaphoneArticle(metaphone($splitedContent));
            $this->entityManager->persist($metaphoneArticle);
            if($i >= 20){
                $this->entityManager->flush();
            }
            $i++;
        }

        $this->entityManager->flush();
    }
}