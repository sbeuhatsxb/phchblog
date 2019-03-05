<?php

namespace App\Command;

use App\Service\IndexArticleActionService;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class IndexArticlesCommand extends Command
{
    protected static $defaultName = 'app:index';

    /**
     * @var IndexArticleActionService $indexArticleActionService
     */
    protected $indexArticleActionService;

    /**
     * IndexArticlesCommand constructor.
     * @param IndexArticleActionService $indexArticleActionService
     */
    public function __construct(IndexArticleActionService $indexArticleActionService) {
        $this->indexArticleActionService = $indexArticleActionService;
        parent::__construct();
    }

    protected function configure()
    {

        $this
            ->setName(self::$defaultName)
            ->setDescription('Generate articles index')
            ->setHelp('Generate articles index');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->indexArticleActionService->indexAllArticle();
    }
}
