<?php
/**
 * Created by PhpStorm.
 * User: sebastien
 * Date: 22/02/19
 * Time: 13:53
 */

namespace App\Controller;

use App\Entity\Article;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Service\LastArticlesService;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\Concept;
use App\Entity\Category;
use App\Entity\Author;


class HomeController extends AbstractController
{

    /**
     * @var LastArticlesService
     */
    protected $lastArticlesService;

    public function __construct(LastArticlesService $lastArticlesService)
    {
        $this->lastArticlesService = $lastArticlesService;
    }

   /**
    * @Route("/",  name="index")
    */
    public function index()
    {
        $lastArticles = $this->lastArticlesService->getLastArticles();

        return $this->render('article_list.html.twig', [
            'articles' => $lastArticles,
        ]);
    }

    /**
     * @Route("/{classname}/{shortname}/{filter}",  name="filtered_list")
     */
    public function filteredList($classname, $filter, $shortname)
    {
        $em = $this->getDoctrine()->getManager();
        $getFilter = $em->getRepository($classname)->findBy(['name' => $filter]);

        $filterId = $getFilter[0]->getId();

        $lastArticles = $this->lastArticlesService->getLastArticles($shortname, $filterId);

        return $this->render('filtered_list.html.twig', [
            'articles' => $lastArticles,
        ]);
    }

}