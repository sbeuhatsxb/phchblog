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

    public function getLastArticles($shortname = null, $filterId = null)
    {
        if (!$shortname || !$filterId) {
            $articles = $this->articleRepository->findLastTwelveArticles();
        } else {
            $articles = $this->articleRepository->findFilteredArticles($shortname, $filterId);
        }

        return $articles;
    }

    public function getArticlesFromSubmit($filter)
    {
        $articles = $this->articleRepository->findFilteredArticlesByForm($filter);

        return $articles;
    }
}