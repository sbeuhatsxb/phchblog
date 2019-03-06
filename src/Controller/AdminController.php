<?php
/**
 * Created by PhpStorm.
 * User: sebastien
 * Date: 21/02/19
 * Time: 16:41
 */

namespace App\Controller;

use App\Service\IndexArticleService;
use EasyCorp\Bundle\EasyAdminBundle\Controller\EasyAdminController;

class AdminController extends EasyAdminController
{
    /**
     * @var IndexArticleService $indexService
     */
    private $indexArticleService;

    function __construct(IndexArticleService $indexArticleService)
    {
        $this->indexArticleService = $indexArticleService;
    }

    public function createNewUserEntity()
    {
        return $this->get('fos_user.user_manager')->createUser();
    }

    public function persistUserEntity($user)
    {
        $this->get('fos_user.user_manager')->updateUser($user, false);
        parent::persistEntity($user);
    }

    public function updateUserEntity($user)
    {
        $this->get('fos_user.user_manager')->updateUser($user, false);
        parent::updateEntity($user);
    }

    public function indexArticlesAction()
    {

        $this->indexArticleService->indexAllArticle();

    }
}