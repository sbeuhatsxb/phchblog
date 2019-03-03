<?php

namespace App\Repository;

use App\Entity\Article;
use App\Entity\ArticleMetaphone;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method ArticleMetaphone|null find($id, $lockMode = null, $lockVersion = null)
 * @method ArticleMetaphone|null findOneBy(array $criteria, array $orderBy = null)
 * @method ArticleMetaphone[]    findAll()
 * @method ArticleMetaphone[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ArticleMetaphoneRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, ArticleMetaphone::class);
    }

    public function findFilteredArticlesByForm($filterArray)
    {
        $qb = $this->createQueryBuilder('a')
            ->join(Article::class, 'la')
            ->where('la.isPublished = true')
            ->orderBy('la.createdAt', 'DESC');

        foreach ($filterArray as $filter) {
            $qb->andWhere('a.metaphoneArticle LIKE :filter')
                ->setParameter('filter', '%' . metaphone($filter) . '%');
        }

        $qb->getQuery()->getResult();
        return $qb;
    }
}
