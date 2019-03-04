<?php

namespace App\Repository;

use App\Entity\Article;
use App\Entity\LexicalIndex;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method LexicalIndex|null find($id, $lockMode = null, $lockVersion = null)
 * @method LexicalIndex|null findOneBy(array $criteria, array $orderBy = null)
 * @method LexicalIndex[]    findAll()
 * @method LexicalIndex[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class LexicalIndexRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, LexicalIndex::class);
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

    // /**
    //  * @return LexicalIndex[] Returns an array of LexicalIndex objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('l')
            ->andWhere('l.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('l.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?LexicalIndex
    {
        return $this->createQueryBuilder('l')
            ->andWhere('l.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
