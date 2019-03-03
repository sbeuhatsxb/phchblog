<?php

namespace App\Command;

use App\Service\JobService;
use Symfony\Component\Console\Command\Command;

use JMS\JobQueueBundle\Entity\Job;
use Psr\Log\LoggerInterface;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use App\Repository\JobRepository;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerAwareTrait;

abstract class BaseCommand extends Command implements ContainerAwareInterface
{
    use ContainerAwareTrait;

    CONST CLI_DATE_FORMAT = 'Y-m-d H:i:s';

    protected static $jmsJobIdOption = 'jms-job-id';

    /**
     * @var JobRepository
     */
    protected $jobRepository;

    /**
     * @var JobService
     */
    protected $jobService;

    /**
     * @var LoggerInterface
     */
    protected $logger;

    /**
     * TODO: better injection here than __construct()
     * @param string|null $name
     * @param JobRepository $jobRepository
     * @param JobService $jobService
     * @param LoggerInterface $logger
     */
    public function __construct(string $name = null, JobRepository $jobRepository, JobService $jobService, LoggerInterface $logger)
    {
        parent::__construct($name);
        $this->jobRepository = $jobRepository;
        $this->jobService = $jobService;
        $this->logger = $logger;
    }

    /**
     *
     * @param InputInterface $input
     * @return Job|null
     */
    protected function getJob(InputInterface $input)
    {
        if ($this->hasJob($input)) {
            $jobId = $input->getOption(self::$jmsJobIdOption);

            return $this->jobRepository->find($jobId);
        }

        return null;
    }

    /**
     * @param InputInterface $input
     * @return boolean
     */
    protected function hasJob(InputInterface $input)
    {
        return $input->hasOption(self::$jmsJobIdOption) && $input->getOption(self::$jmsJobIdOption);
    }

    /**
     * @return string
     */
    protected function nowPrefix()
    {
        return $this->formatDate(new \DateTime()).' - ';
    }

    /**
     *
     * @param \DateTime $date
     * @return string
     */
    protected function formatDate($date)
    {
        return $date ? $date->format(self::CLI_DATE_FORMAT) : 'NULL_DATE';
    }

    /**
     *
     * @param OutputInterface $output
     * @param string $message
     */
    protected function writelnPrefixed($output, $message)
    {
        $output->writeln($this->nowPrefix().$message);
    }
}
