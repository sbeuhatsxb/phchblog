<?php

namespace App\Repository;

use App\Entity\Concepts;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Concepts|null find($id, $lockMode = null, $lockVersion = null)
 * @method Concepts|null findOneBy(array $criteria, array $orderBy = null)
 * @method Concepts[]    findAll()
 * @method Concepts[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ConceptsRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Concepts::class);
    }

    // /**
    //  * @return Concepts[] Returns an array of Concepts objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('c.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Concepts
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
