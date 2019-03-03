<?php
/**
 * Created by PhpStorm.
 * User: sebastien
 * Date: 02/03/19
 * Time: 19:46
 */

namespace App\EventListener;

use App\Entity\ArticleMetaphone;
use Doctrine\Common\Persistence\Event\LifecycleEventArgs;
use App\Entity\Article;

class MetaphoneArticleContentEvent
{

    public function postPersist(LifecycleEventArgs $args)
    {
        $entity = $args->getObject();

        if (!$entity instanceof Article) {
            return;
        }

        $entityManager = $args->getObjectManager();

        $metaphoneArticle = new ArticleMetaphone();
        $metaphoneArticle->setLinkedArticle($entity);
        $splitedContent = substr($entity->getContent(), 0, 16383);
        $metaphoneArticle->setMetaphoneArticle(metaphone($splitedContent));
        $entityManager->persist($metaphoneArticle);
        $entityManager->flush();

    }


}