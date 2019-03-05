<?php

namespace App\Command;

use App\Service\IndexImageManualyUploadedService;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class IndexImagesCommand extends Command
{
    protected static $defaultName = 'app:index:image';

    /**
     * @var IndexImageManualyUploadedService $indexImageManualyUploadedService
     */
    protected $indexImageManualyUploadedService;

    /**
     * IndexArticlesCommand constructor.
     * @param IndexImageManualyUploadedService $indexImageManualyUploadedService
     */
    public function __construct(IndexImageManualyUploadedService $indexImageManualyUploadedService) {
        $this->indexImageManualyUploadedService = $indexImageManualyUploadedService;
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
        $this->indexImageManualyUploadedService->indexImage();
    }
}
