<?php
/**
 * Created by PhpStorm.
 * User: sebastien
 * Date: 22/02/19
 * Time: 13:53
 */

namespace App\Controller;

use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Service\LastArticlesService;


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

}