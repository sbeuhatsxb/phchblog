<?php

namespace App\Service;

use App\Repository\JobRepository;
use JMS\JobQueueBundle\Entity\Job;
use Doctrine\ORM\EntityManager;

class JobService
{
    /**
     * @var JobRepository
     */
    protected $jobRepository;

    /**
     * @var string
     */
    protected $cronDisabledFlagPath;

    /**
     * @param JobRepository $jobRepository
     * @param string $cronDisabledFlagPath
     */
    public function __construct(JobRepository $jobRepository, string $cronDisabledFlagPath)
    {
        $this->jobRepository = $jobRepository;
        $this->cronDisabledFlagPath = $cronDisabledFlagPath;
    }

    /**
     * @param string $command
     * @param array $args
     * @param string $queue
     * @param null|callable $callable a callable that receives the job to do some initialization work for instance
     * @return Job
     * @throws \Throwable
     */
    public function getOrCreateIfNotExists($command, $args = [], $queue = Job::DEFAULT_QUEUE, $callable = null): Job
    {
        return $this->jobRepository->getEntityManager()->transactional(
            function (EntityManager $em) use ($command, $args, $queue, $callable) {
                if (null !== $job = $this->jobRepository->findActiveJob($command, $args)) {
                    return $job;
                }
                return $this->create($command, $args, $queue, $callable);
            });
    }

    /**
     * @param string $command
     * @param array $parameters
     * @param string $queue
     * @param null|callable $callable a callable that receives the job to do some initialization work for instance
     *
     * @return Job
     */
    public function create($command, $parameters = [], $queue = Job::DEFAULT_QUEUE, $callable = null)
    {
        return $this->jobRepository->getEntityManager()->transactional(function (EntityManager $em) use ($command, $parameters, $queue, $callable) {
            $job = new Job($command, $parameters, false, $queue);
            if ($callable !== null) {
                $callable($job);
            }
            $job->setState(Job::STATE_PENDING);
            $em->persist($job);

            return $job;
        });
    }

    /**
     * @return bool
     */
    public function getCronEnabled()
    {
        return !file_exists($this->cronDisabledFlagPath);
    }

    /**
     * @throws \Exception
     */
    public function enableCron()
    {
        if (file_exists($this->cronDisabledFlagPath) && !@unlink($this->cronDisabledFlagPath)) {
            throw new \Exception('Could not enable cron. Is '.$this->cronDisabledFlagPath.' writeable ?');
        }
    }

    /**
     * @throws \Exception
     */
    public function disableCron()
    {
        if (!file_exists($this->cronDisabledFlagPath) && !@touch($this->cronDisabledFlagPath)) {
            throw new \Exception('Could not disable cron. Is '.$this->cronDisabledFlagPath.' writeable ?');
        }
    }
}
