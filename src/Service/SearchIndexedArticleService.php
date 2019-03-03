<?php
/**
 * Created by PhpStorm.
 * User: sebastien
 * Date: 03/03/19
 * Time: 18:04
 */

namespace App\Service;

use App\Repository\ArticleMetaphoneRepository;


class SearchIndexedArticleService
{
    /**
     * @var ArticleMetaphoneRepository
     */
    protected $metaphoneRepo;

    public function __construct(ArticleMetaphoneRepository $metaphoneRepo)
    {
        $this->metaphoneRepo = $metaphoneRepo;
    }

    public function getArticlesFromSubmit($filterArray)
    {

        $articles = $this->metaphoneRepo->findFilteredArticlesByForm($filterArray);

        return $articles;
    }
}