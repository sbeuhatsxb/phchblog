<?php

namespace App\Command;

use App\Service\JobService;
use JMS\JobQueueBundle\Console\CronCommand;
use JMS\JobQueueBundle\Entity\Job;
use Symfony\Component\Config\Definition\Exception\InvalidConfigurationException;
use App\Repository\JobRepository;
use Psr\Log\LoggerInterface;

abstract class BaseCronCommand extends BaseCommand implements CronCommand
{
    /**
     * @var int
     */
    protected $frequencyInSeconds;

    /**
     * @var string
     */
    protected $dateTimePattern;

    /**
     * @var Job[]
     */
    protected $jobDependencies = [];

    /**
     * @var object[]
     */
    protected $relatedEntities = [];

    /**
     * @var array
     */
    protected $jobArguments = [];

    /**
     * @param string|null $name
     * @param JobRepository $jobRepository
     * @param LoggerInterface $logger
     * @param JobService $jobService
     */
    public function __construct(
        string $name = null,
        JobRepository $jobRepository,
        JobService $jobService,
        LoggerInterface $logger
    ) {
        parent::__construct($name, $jobRepository, $jobService, $logger);
    }

    /**
     *
     * @param int $frequencyInSeconds
     * @return $this
     */
    protected function setFrequencyInSeconds($frequencyInSeconds)
    {
        $this->frequencyInSeconds = $frequencyInSeconds;

        return $this;
    }

    /**
     *
     * @return int
     */
    private function getFrequencyInSeconds()
    {
        return $this->frequencyInSeconds;
    }

    /**
     *
     * @return boolean
     */
    private function hasFrequencyInSeconds()
    {
        return $this->frequencyInSeconds !== null;
    }

    /**
     *
     * @param \DateTime $now
     * @param \DateTime $lastRunAt
     * @return boolean
     */
    private function evaluateFrequencyInSeconds(\DateTime $now, \DateTime $lastRunAt)
    {
        return $now->getTimestamp() - $lastRunAt->getTimestamp() >= $this->getFrequencyInSeconds();
    }

    /**
     *
     * @param string $dateTimePattern
     * @return self
     */
    protected function setDateTimePattern($dateTimePattern)
    {
        $this->dateTimePattern = $dateTimePattern;

        return $this;
    }

    /**
     * @return boolean
     */
    private function hasDateTimePattern()
    {
        return $this->dateTimePattern !== null;
    }

    /**
     *
     * @return string
     */
    private function getDateTimePattern()
    {
        return $this->dateTimePattern;
    }

    /**
     *
     * @param \DateTime $now
     * @param \DateTime $lastRunAt
     * @return boolean
     */
    private function evaluateDateTimePattern(\DateTime $now, \DateTime $lastRunAt)
    {
        $dateTimePattern = $this->getDateTimePattern();
        $matches = null;
        if (!preg_match('/^(\d{4}|\*)\-(\d{2}|\*)\-(\d{2}|\*) (\d{2}|\*):(\d{2}|\*)$/', $dateTimePattern, $matches)) {
            throw new InvalidConfigurationException(
                'Bad DateTime pattern for cron '.$this->getName().': '.$this->getDateTimePattern()
            );
        }
        $year = $matches[1] == '*' ? $now->format('Y') : $matches[1];
        $month = $matches[2] == '*' ? $now->format('m') : $matches[2];
        $day = $matches[3] == '*' ? $now->format('d') : $matches[3];
        $hour = $matches[4] == '*' ? $now->format('H') : $matches[4];
        $minute = $matches[5] == '*' ? $now->format('i') : $matches[5];
        $second = '00';

        /** @var \DateTime $dateCandidate */
        $dateCandidate = \DateTime::createFromFormat(
            self::CLI_DATE_FORMAT,
            $year.'-'.$month.'-'.$day.' '.$hour.':'.$minute.':'.$second
        );

        return $now->getTimestamp() >= $dateCandidate->getTimestamp() && $lastRunAt->getTimestamp() < $dateCandidate->getTimestamp();
    }

    public function shouldBeScheduled(\DateTime $lastRunAt): bool
    {
        if (!$this->jobService->getCronEnabled()) {
            $this->logger->info(__METHOD__.' cron is disabled');

            return false;
        }

        $now = new \DateTime();
        $this->logger->debug(__METHOD__." ".$this->formatDate($now)." ".$this->formatDate($lastRunAt));
        /** @var Job $job */
        $job = $this->jobRepository->findActiveJob($this->getName(), [], false);
        if ($job !== null) {
            $this->logger->info(
                "Active Job ".$this->getName()."[@id=".$job->getId()." @state=".$job->getState()."] found"
            );
            return false;
        }

        if ($this->hasFrequencyInSeconds()) {
            return $this->evaluateFrequencyInSeconds($now, $lastRunAt) && $this->wouldDoSomething();
        }
        if ($this->hasDateTimePattern()) {
            return $this->evaluateDateTimePattern($now, $lastRunAt) && $this->wouldDoSomething();
        }
        $this->logger->error(get_class($this).' must set either frequencyInSeconds or dateTimePattern in configure()');

        return false;
    }

    /**
     * Instead of flooding the job history for nothing you can here return false on some condition you handle
     *
     * @return boolean
     */
    protected function wouldDoSomething(): bool
    {
        return true;
    }

    /**
     * Method to be overriden by subclass
     *
     * @return object[]
     */
    protected function getJobRelatedEntities()
    {
        return $this->relatedEntities;
    }

    /**
     * @param object[] $relatedEntities
     *
     * @return $this
     */
    protected function setJobRelatedEntities($relatedEntities)
    {
        $this->relatedEntities = $relatedEntities;

        return $this;
    }

    /**
     * @return Job[]
     */
    protected function getJobDependencies()
    {
        return $this->jobDependencies;
    }

    /**
     * @param Job[] $jobDependencies
     *
     * @return $this
     */
    protected function setJobDependencies($jobDependencies)
    {
        $this->jobDependencies = $jobDependencies;

        return $this;
    }

    /**
     * @return array the arguments given to the command
     */
    protected function getJobArguments()
    {
        return $this->jobArguments;
    }

    protected function setJobArguments($jobArguments)
    {
        $this->jobArguments = $jobArguments;

        return $this;
    }

    public function createCronJob(\DateTime $lastRunAt): Job
    {
        return $this->jobService->getOrCreateIfNotExists(
            $this->getName(),
            $this->getJobArguments(),
            JobRepository::QUEUE_CRON,
            function (Job $job) {
                foreach ($this->getJobRelatedEntities() as $relatedEntity) {
                    $job->addRelatedEntity($relatedEntity);
                }

                foreach ($this->getJobDependencies() as $jobDependency) {
                    $job->addDependency($jobDependency);
                }
            }
        );
    }
}
