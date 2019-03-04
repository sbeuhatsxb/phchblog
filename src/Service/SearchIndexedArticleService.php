<?php
/**
 * Created by PhpStorm.
 * User: sebastien
 * Date: 03/03/19
 * Time: 18:04
 */

namespace App\Service;

use App\Repository\LexicalIndexRepository;


class SearchIndexedArticleService
{
    /**
     * @var LexicalIndexRepository $lexicalIndexRepository
     */
    protected $lexicalIndexRepository;

    public function __construct(LexicalIndexRepository $lexicalIndexRepository)
    {
        $this->lexicalIndexRepository = $lexicalIndexRepository;
    }

    public function getArticlesFromExactSubmit($filterArray)
    {
        $articles = $this->lexicalIndexRepository->findFilteredArticlesByExactForm($filterArray);

        return $articles;
    }

    public function getArticlesFromApproximalSubmit($filterArray)
    {
        $articles = $this->lexicalIndexRepository->findFilteredArticlesByApproximalForm($filterArray);

        return $articles;
    }
}