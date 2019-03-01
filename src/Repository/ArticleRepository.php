<?php

namespace App\Repository;

use App\Entity\Article;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Query\AST\Functions\SubstringFunction;
use Symfony\Bridge\Doctrine\RegistryInterface;
use Doctrine\ORM\Query\ResultSetMappingBuilder;

/**
 * @method Article|null find($id, $lockMode = null, $lockVersion = null)
 * @method Article|null findOneBy(array $criteria, array $orderBy = null)
 * @method Article[]    findAll()
 * @method Article[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ArticleRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Article::class);
    }

    public function findLastTwelveArticles(){

        return $this->createQueryBuilder('a')
            ->where('a.isPublished = true')
            ->orderBy('a.createdAt', 'DESC')
            ->setMaxResults(12)
            ->getQuery()
            ->getResult()
            ;

    }

    public function findFilteredArticles($shortname, $filterId){

        return $this->createQueryBuilder('a')
            ->leftJoin('a.linked'.$shortname, 'c')
            ->where('c.id = :'. $shortname . '_id')
            ->orderBy('a.createdAt', 'DESC')
            ->setParameter($shortname.'_id', $filterId)
            ->andWhere('a.isPublished = true')
            ->getQuery();
    }

    public function findFilteredArticlesByForm($filterArray){

                    $qb = $this->createQueryBuilder('a')
                ->where('a.isPublished = true')
                ->orderBy('a.createdAt', 'DESC');

            foreach($filterArray as $filter) {
                $qb->andWhere('a.content LIKE :filter')
                ->setParameter('filter', '%'.$filter.'%');
            }

            $qb->getQuery()->getResult();
            return $qb;
        }
}
