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

    private function linkedPublishedArticleQuery(){
        $qb = $this->createQueryBuilder('l')
            ->join(Article::class, 'a')
            ->where('a.isPublished = true')
            ->orderBy('a.createdAt', 'DESC');

        return $qb;
    }

    public function findFilteredArticlesByExactForm($filterArray)
    {
        $qb = $this->linkedPublishedArticleQuery();

        foreach ($filterArray as $filter) {
            $qb->andWhere('l.word LIKE :filter')
                ->setParameter('filter', '%' . $filter . '%');
        }
        
        return $qb;
    }

    public function findFilteredArticlesByApproximalForm($filterArray)
    {
        $qb = $this->linkedPublishedArticleQuery();

        foreach ($filterArray as $filter) {
            $qb->andWhere('l.metaphone LIKE :filter')
                ->setParameter('filter', '%' . metaphone($filter) . '%');
        }

        return $qb;
    }

}
