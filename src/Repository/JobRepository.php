<?php

namespace App\Repository;

use App\Entity\Catalog\ProductCategory;
use App\Entity\Customer\CustomerGroup;
use JMS\JobQueueBundle\Entity\Repository\JobManager;
use Symfony\Bridge\Doctrine\RegistryInterface;
use JMS\JobQueueBundle\Entity\Job;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Query\Parameter;
use Doctrine\DBAL\Types\Type;
use Doctrine\DBAL\Connection;
use Doctrine\ORM\Query;
use JMS\JobQueueBundle\Entity\Repository\JobRepository as JMSJobRepository;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepositoryInterface;

/**
 * @method Job|null find($id, $lockMode = null, $lockVersion = null)
 * @method Job|null findOneBy(array $criteria, array $orderBy = null)
 * @method Job[]    findAll()
 * @method Job[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class JobRepository extends AbstractRepository implements ServiceEntityRepositoryInterface
{
    const QUEUE_FO = 'FO';
    const QUEUE_BO = 'BO';
    const QUEUE_CRON = 'CRON';
    const QUEUE_IMPORT = 'IMPORT';

    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Job::class);
    }

    public function findActiveJob($command, array $args = [], $useArgs = true): ?Job
    {
        return $this->getActiveJobQuery($command, $args, $useArgs)
            ->setMaxResults(1)
            ->getOneOrNullResult();
    }

    /**
     * @param string $command
     * @param array $args
     * @param boolean $useArgs
     *
     * @return Query
     */
    protected function getActiveJobQuery($command, array $args = [], $useArgs = true)
    {
        $params = new ArrayCollection();
        $sql = "SELECT j FROM JMSJobQueueBundle:Job j WHERE j.command = :command AND j.queue <> :subjob_queue";
        $params->add(new Parameter('command', $command));
        $params->add(new Parameter('subjob_queue', self::QUEUE_IMPORT));
        if ($useArgs) {
            $sql .= ' AND j.args = :args';
            $params->add(new Parameter('args', $args, Type::JSON_ARRAY));
        }

        $states = [Job::STATE_PENDING, Job::STATE_RUNNING];
        if (!empty($states)) {
            $sql .= " AND j.state IN (:states)";
            $params->add(new Parameter('states', $states, Connection::PARAM_STR_ARRAY));
        }

        return $this->getEntityManager()->createQuery($sql)
            ->setParameters($params);
    }

    /**
     * @param string $command
     *
     * @return mixed
     */
    public function getNonSuccessfulJobs($command)
    {
        $states = [Job::STATE_CANCELED, Job::STATE_FAILED, Job::STATE_INCOMPLETE, Job::STATE_TERMINATED];
        $qb = $this->createQueryBuilder('j');
        $qb
            ->where(
                $qb->expr()->like('j.command', ':command'),
                $qb->expr()->in('j.state', ':states')
            )
            ->setParameters(['states' => $states, 'command' => $command]);

        return $qb->getQuery()->execute();
    }

    public function getImportJobSince(\DateTime $since)
    {
        $qb = $this->createQueryBuilder('j');
        $qb
            ->where(
                $qb->expr()->like('j.command', ':command'),
                $qb->expr()->gte('j.createdAt', ':since')
            )
            ->setParameters(['since' => $since, 'command' => 'app:import:%']);

        return $qb->getQuery()->execute();
    }

    public function getLastJob($command): ?Job
    {
        return $this->createQueryBuilder('j')
            ->where(
                'j.command = :command',
                'j.queue <> :subjob_queue'
            )
            ->orderBy('j.id', 'desc')
            ->setMaxResults(1)
            ->setParameters(['command' => $command, 'subjob_queue' => self::QUEUE_IMPORT])
            ->getQuery()
            ->getOneOrNullResult();
    }

    public function findJobsWithForeignKeyConstraint(): array
    {
        $callback = function ($result) {
            return (isset($result['id'])) ? $result['id'] : null;
        };

        return array_merge(
            array_map($callback, $this->createQueryBuilder('j')
                ->select('j.id')
                ->innerJoin(ProductCategory::class, 'c', 'WITH', 'j.id = c.lastRefreshJob')
                ->getQuery()
                ->getArrayResult()),
            array_map($callback, $this->createQueryBuilder('j')
                ->select('j.id')
                ->innerJoin(CustomerGroup::class, 'g', 'WITH', 'j.id = g.lastRefreshJob')
                ->getQuery()
                ->getArrayResult()
            ));
    }
}
