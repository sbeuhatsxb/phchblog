<?php
/**
 * Created by PhpStorm.
 * User: sebastien
 * Date: 02/03/19
 * Time: 19:46
 */

namespace App\EventListener;

use App\Service\IndexArticleActionService;
use Doctrine\Common\Persistence\Event\LifecycleEventArgs;
use App\Entity\Article;

class IndexLastSubmitedArticleEvent
{
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