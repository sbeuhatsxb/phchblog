<?php
/**
 * Created by PhpStorm.
 * User: sebastien
 * Date: 25/02/19
 * Time: 10:12
 */

namespace App\Service;

use App\Repository\ArticleRepository;

class LastArticlesService
{
    /**
     * @var ArticleRepository
     */
    protected $articleRepository;

    public function __construct(ArticleRepository $articleRepository)
    {
        $this->articleRepository = $articleRepository;
    }

    public function getLastArticles(){
        $articles = $this->articleRepository->findLastTwelveArticles();

        return $articles;
    }
}