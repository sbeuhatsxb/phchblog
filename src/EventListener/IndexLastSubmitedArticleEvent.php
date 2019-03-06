<?php
/**
 * Created by PhpStorm.
 * User: sebastien
 * Date: 02/03/19
 * Time: 19:46
 */

namespace App\EventListener;

use App\Service\IndexArticleService;
use Doctrine\Common\Persistence\Event\LifecycleEventArgs;
use App\Entity\Article;

class IndexLastSubmitedArticleEvent
{
    /**
     * @var IndexArticleService $indexArticleActionService
     */
    protected $indexArticleActionService;

    /**
     * IndexArticlesCommand constructor.
     * @param IndexArticleService $indexArticleActionService
     */
    public function __construct(IndexArticleService $indexArticleActionService) {
        $this->indexArticleActionService = $indexArticleActionService;
    }


    public function postPersist(LifecycleEventArgs $args)
    {
        $entity = $args->getObject();

        if (!$entity instanceof Article) {
            return;
        }

        $entityManager = $args->getObjectManager();

        $this->indexArticleActionService->indexArticle([$entity]);

    }


}