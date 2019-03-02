<?php

namespace App\Repository;

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

    // /**
    //  * @return ArticleMetaphone[] Returns an array of ArticleMetaphone objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('a.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?ArticleMetaphone
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
