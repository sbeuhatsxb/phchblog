<?php

namespace App\Repository;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\QueryBuilder;

abstract class AbstractRepository extends ServiceEntityRepository
{

    const CACHE_NONE = 0;
    const CACHE_QUERY = 1;
    const CACHE_RESULT = 2;
    // Not supported for now
    const CACHE_SECOND_LEVEL = 4;
    const CACHE_SAFE = self::CACHE_QUERY;
    const CACHE_ALL = self::CACHE_QUERY | self::CACHE_RESULT | self::CACHE_SECOND_LEVEL;

    /**
     * {@inheritDoc}
     * @see \Doctrine\ORM\EntityRepository::getEntityManager()
     * @return EntityManager
     */
    public function getEntityManager()
    {
        return parent::getEntityManager();
    }

    /**
     * @param string $alias
     * @return QueryBuilder
     */
    protected function createDeleteQueryBuilder($alias)
    {
        return $this->_em->createQueryBuilder()
            ->delete($this->_entityName, $alias);
    }

    /**
     * @param string $alias
     * @return QueryBuilder
     */
    protected function createUpdateQueryBuilder($alias)
    {
        return $this->_em->createQueryBuilder()
            ->update($this->_entityName, $alias);
    }

    /**
     * @param object $entity
     * @throws \Exception
     * @return object
     */
    public function refresh(object &$entity)
    {
        if (!$entity->getId()) {
            throw new \Exception('Can not refresh a non persisted entity');
        }
        $entity = $this->find($entity->getId());

        return $entity;
    }

    /**
     * @param callable $callable
     * @param number $batchSize if set to null, you have to deal your self with flush() & clear() methods
     * @return number the number of entities we iterated on
     */
    public function iterate($callable, $batchSize = 50)
    {
        $em = $this->getEntityManager();
        $i = 0;
        foreach ($this->createQueryBuilder('e')->getQuery()->iterate() as $row) {
            $callable($row[0]);
            if ($batchSize && $i % $batchSize === 0) {
                $em->flush();
                $em->clear();
            }
            $i++;
        }
        $em->flush();
        $em->clear();

        return $i;
    }

    /**
     * @param QueryBuilder $qb
     * @param string $select
     * @param boolean $cache
     * @return \Doctrine\ORM\Query
     */
    protected function handleSelectAndCacheArgs(QueryBuilder $qb, $select = null, $cache = false)
    {
        // TODO
        // cache lifetime settings, ... etc.
        // https://www.doctrine-project.org/projects/doctrine-orm/en/2.6/reference/caching.html#integrating-with-the-orm
        if ($select) {
            $qb->select($select);
        }
        $query = $qb->getQuery();
        if ($cache & self::CACHE_QUERY) {
            // this one is normally already enabled because we configured query cache driver
            $query->useQueryCache(true);
        }
        if ($cache & self::CACHE_RESULT) {
            $query->useResultCache(true);
        }

        return $query;
    }
}
