<?php

namespace App\Command;

use App\Repository\JobRepository;
use App\Service\IndexArticleActionService;
use App\Service\JobService;
use Psr\Log\LoggerInterface;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class IndexArticlesCommand extends BaseCronCommand
{
    protected static $defaultName = 'app:index';

    /**
     * @var IndexArticleActionService $indexArticleActionService
     */
    protected $indexArticleActionService;

    /**
     * IndexArticlesCommand constructor.
     * @param string|null $name
     * @param JobRepository $jobRepository
     * @param LoggerInterface $logger
     * @param JobService $jobService
     * @param IndexArticleActionService $indexArticleActionService
     */
    public function __construct(
        string $name = null,
        JobRepository $jobRepository,
        LoggerInterface $logger,
        JobService $jobService,
        IndexArticleActionService $indexArticleActionService
    ) {
        parent::__construct($name, $jobRepository, $jobService, $logger);
        $this->indexArticleActionService = $indexArticleActionService;
    }

    protected function configure()
    {
        $this
            // every day at 11:00 AM
            ->setDateTimePattern('*-*-* 11:00')
            ->setName(self::$defaultName)
            ->setDescription('Update periodicaly the restaurants menus')
            ->setHelp('This command update Restaurant\'smenus list');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->indexArticleActionService->indexArticle();
    }
}
