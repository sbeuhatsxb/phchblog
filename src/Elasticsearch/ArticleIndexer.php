<?php

namespace App\Elasticsearch;

use App\Entity\Article;
use App\Repository\ArticleRepository;
use Elastica\Client;
use Elastica\Document;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class ArticleIndexer
{
    private $client;
    private $articleRepository;
    private $router;

    public function __construct(Client $client, ArticleRepository $articleRepository, UrlGeneratorInterface $router)
    {
        $this->client = $client;
        $this->articleRepository = $articleRepository;
        $this->router = $router;
    }

    public function buildDocument(Article $article)
    {
        $summary = mb_substr($article->getContent(), 0, 160);
        $linkedBrands = implode(' ,', $article->getLinkedBrand()->getValues());

        return new Document(
            $article->getId(), // Manually defined ID
            [
                'id' => $article->getId(),
                'title' => $article->getTitle(),
                'content' => $article->getContent(),
                'price' => $article->getPrice(),
                'linkedBrands' => $linkedBrands,
                'linkedImage' => $article->getLinkedImage()->getImage(),
                'summary'=> $summary,
                //                'date' => $article->getcreatedAt()->format('M d, Y'),
//                'url' => $this->router->generate('blog_article', ['slug' => $article->getSlug()], UrlGeneratorInterface::ABSOLUTE_PATH),
                // Not indexed but needed for display

            ],
            "articles" // Types are deprecated, to be removed in Elastic 7
        );
    }

    public function indexAllDocuments($indexName)
    {
        $allPosts = $this->articleRepository->findAll();
        $index = $this->client->getIndex($indexName);

        $documents = [];
        foreach ($allPosts as $post) {
            $documents[] = $this->buildDocument($post);
        }

        $index->addDocuments($documents);
        $index->refresh();
    }
}