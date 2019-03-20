<?php
/**
 * Created by PhpStorm.
 * User: sebastien
 * Date: 01/03/19
 * Time: 14:38
 */

namespace App\Controller;

use App\Entity\Article;
use App\Repository\ArticleRepository;
use App\Service\CreatePreviewArticleService;
use App\Service\PaginationService;
use App\Service\SearchAndScoreIndexedArticleService;
use Doctrine\ORM\EntityManagerInterface;
use Elastica\Query;
use Elastica\Client;
use Elastica\Query\BoolQuery;
use Elastica\Query\MultiMatch;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use App\Service\SearchBarFormService;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;


class SearchBarController extends Controller
{
    /**
     * @var CreatePreviewArticleService $createPreviewArticleService
     */
    protected $createPreviewArticleService;

    /**
     * @var PaginationService
     */
    protected $paginationService;


    /**
     * SearchBarController constructor.
     * @param CreatePreviewArticleService $createPreviewArticleService
     * @param PaginationService $paginationService
     */
    public function __construct(CreatePreviewArticleService $createPreviewArticleService, PaginationService $paginationService)
    {
        $this->createPreviewArticleService = $createPreviewArticleService;
        $this->paginationService = $paginationService;
    }

    /**
     * @Route("/recherche", name="searchbar")
     * @Method("GET")
     * @param Request $request
     * @param Client $client
     * @return Response
     */
    public function search(Request $request, Client $client)
    {
        if (!$request->isXmlHttpRequest()) {
            return $this->render('search.html.twig');
        }

        $query = $request->query->get('q', '');
        $limit = $request->query->get('l', 12);

        $match = new MultiMatch();
        $match->setQuery($query);
        $match->setFields(["id","title", "content", "author", "url"]);

        $bool = new BoolQuery();
        $bool->addMust($match);
        $bool->addShould($match);

        $elasticaQuery = new Query($bool);
        $elasticaQuery->setSize($limit);

        $foundPosts = $client->getIndex('shop')->search($elasticaQuery);
        $results = [];
        foreach ($foundPosts as $post) {
            $results[] = $results[] = $post->getSource();
        }

        return $this->json($results);


    }

}